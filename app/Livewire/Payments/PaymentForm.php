<?php

namespace App\Livewire\Payments;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\PaymentType;
use App\Models\Property;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentForm extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public ?Payment $payment = null;
    public bool $editing = false;

    #[Rule('required|integer|exists:properties,id')]
    public ?int $property_id = null;

    #[Rule('nullable|integer|exists:contracts,id')]
    public ?int $contract_id = null;

    #[Rule('required|integer|exists:payment_types,id')]
    public ?int $payment_type_id = null;

    #[Rule('required|numeric|min:0')]
    public ?string $amount = null;

    #[Rule('required|date')]
    public ?string $paid_at = null;

    #[Rule('required|string|in:cash,cheque,bank_transfer,credit_card')]
    public ?string $payment_method = 'cash';

    #[Rule('nullable|string|max:100')]
    public ?string $reference_number = null;

    #[Rule('nullable|string|max:1000')]
    public ?string $description = null;

    #[Rule('nullable|array|max:5')]
    public $attachments = [];

    #[Rule('nullable|array')]
    public $existingAttachments = [];
    public $attachmentsToRemove = [];

    public function mount(?Payment $payment = null): void
    {
        Log::info('PaymentForm mounting', ['payment_passed' => !is_null($payment), 'payment_id' => $payment?->id]);

        if ($payment && $payment->exists) {
            $this->payment = $payment;
            $this->editing = true;
            Log::info('PaymentForm mounting in EDIT mode', ['assigned_payment_id' => $this->payment?->id]);
            Gate::authorize('edit payments');
            $this->property_id = $this->payment->property_id;
            $this->contract_id = $this->payment->contract_id;
            $this->payment_type_id = $this->payment->payment_type_id;
            $this->amount = (string) $this->payment->amount;
            $this->paid_at = $this->payment->paid_at?->format('Y-m-d');
            $this->payment_method = $this->payment->payment_method;
            $this->reference_number = $this->payment->reference_number;
            $this->description = $this->payment->description;
            $this->existingAttachments = $this->payment->getMedia('receipts')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'file_name' => $media->file_name,
                    'url' => $media->getUrl(),
                ];
            })->toArray();
            $this->attachmentsToRemove = [];
        } else {
            Log::info('PaymentForm mounting in CREATE mode');
            Gate::authorize('create payments');
            $this->paid_at = Carbon::today()->format('Y-m-d');
            $this->editing = false;
            $this->payment = null;
        }

        Log::info('PaymentForm mount finished', ['editing_state' => $this->editing, 'final_payment_id' => $this->payment?->id]);
    }

    #[Computed]
    public function properties(): Collection
    {
        return Property::orderBy('name')->get(['id', 'name',])->mapWithKeys(function ($property) {
            return [$property->id => $property->name];
        });
    }

    #[Computed]
    public function paymentTypes(): Collection
    {
        return PaymentType::orderBy('name')->pluck('name', 'id');
    }

    #[Computed]
    public function contracts(): Collection
    {
        if (!$this->property_id) {
            return collect();
        }
        return Contract::where('property_id', $this->property_id)
            ->orderBy('cstart', 'desc')
            ->get(['id', 'name', 'cstart', 'cend'])
            ->mapWithKeys(function ($contract) {
                $dateRange = Carbon::parse($contract->cstart)->format('d/m/Y') . ' - ' .
                    Carbon::parse($contract->cend)->format('d/m/Y');
                return [$contract->id => $contract->name . ' (' . $dateRange . ')'];
            });
    }

    public function updatedPropertyId($value): void
    {
        $this->contract_id = null;
        $this->resetValidation('contract_id');
    }

    public function updatedAttachments(): void
    {
        Log::info('updatedAttachments called.', ['current_attachments_count' => count($this->attachments)]);
        try {
            $this->validateOnly('attachments.*', [
                'attachments.*' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png']
            ]);
            Log::info('updatedAttachments validation passed.', ['attachments_after_validation_count' => count($this->attachments)]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('updatedAttachments validation failed.', ['errors' => $e->errors()]);
            $this->reset('attachments');
            $this->dispatch('notify', title: 'Upload Error', message: 'Invalid file(s) selected. Please check type and size.', type: 'error');
        }
    }

    public function removeUpload(string $name, string $key): void
    {
        foreach ($this->attachments as $i => $file) {
            if ($file->getFilename() === $name) {
                unset($this->attachments[$i]);
                break;
            }
        }
        $this->attachments = array_values($this->attachments);
    }

    public function markAttachmentForRemoval(int $mediaId): void
    {
        if (!in_array($mediaId, $this->attachmentsToRemove)) {
            $this->attachmentsToRemove[] = $mediaId;
            $this->existingAttachments = collect($this->existingAttachments)->reject(fn($att) => $att['id'] === $mediaId)->toArray();
        }
    }

    public function unmarkAttachmentForRemoval(int $mediaId): void
    {
        $this->attachmentsToRemove = array_diff($this->attachmentsToRemove, [$mediaId]);
    }

    public function save()
    {
        $validated = $this->validate();
        Log::info('Save method started.', ['validated_keys' => array_keys($validated)]);
        Log::info('Checking attachments before processing in save().', ['attachments_property_count' => count($this->attachments), 'is_attachments_property_empty' => empty($this->attachments)]);

        $payment = null;
        $message = '';

        try {
            DB::beginTransaction();

            if ($this->editing && $this->payment) {
                Gate::authorize('edit payments');
                Log::info('Attempting to update payment', ['payment_id' => $this->payment->id, 'data' => $validated]);
                $updateData = collect($validated)->except('attachments')->toArray();
                $this->payment->update($updateData);
                $payment = $this->payment->fresh();
                $message = 'Payment updated successfully.';
                Log::info('Payment updated successfully', ['payment_id' => $payment->id]);
            } else {
                Gate::authorize('create payments');
                $validated['user_id'] = Auth::id();
                $createData = collect($validated)->except('attachments')->toArray();
                Log::info('Attempting to create payment', ['data' => $createData]);
                $payment = Payment::create($createData);
                $message = 'Payment created successfully.';
                Log::info('Payment created successfully', ['payment_id' => $payment->id]);
            }

            if ($this->editing && !empty($this->attachmentsToRemove)) {
                Log::info('Removing attachments', ['media_ids' => $this->attachmentsToRemove]);
                Media::whereIn('id', $this->attachmentsToRemove)->delete();
                $this->attachmentsToRemove = [];
            }

            if ($payment && !empty($this->attachments)) {
                Log::info('Adding new attachments from property', ['count' => count($this->attachments)]);
                foreach ($this->attachments as $attachment) {
                    try {
                        $payment->addMedia($attachment->getRealPath())
                            ->usingName($attachment->getClientOriginalName())
                            ->toMediaCollection('receipts');
                        Log::info('Attachment added', ['filename' => $attachment->getClientOriginalName()]);
                    } catch (\Exception $mediaError) {
                        Log::error('Error adding media', [
                            'payment_id' => $payment->id ?? 'N/A',
                            'filename' => $attachment->getClientOriginalName(),
                            'error' => $mediaError->getMessage()
                        ]);
                        throw $mediaError;
                    }
                }
            }

            DB::commit();
            Log::info('Database transaction committed', ['mode' => $this->editing ? 'edit' : 'create']);

            $this->reset('attachments');

            $this->dispatch('notify', title: 'Success', message: $message, type: 'success');
            return redirect()->route('payments.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation exception during save', ['mode' => $this->editing ? 'edit' : 'create', 'errors' => $e->errors()]);
            throw $e;
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            DB::rollBack();
            $authMessage = $this->editing ? 'edit' : 'create';
            Log::error('Authorization exception during save', ['mode' => $authMessage, 'error' => $e->getMessage()]);
            $this->dispatch('notify', title: 'Error', message: "You are not authorized to {$authMessage} payments.", type: 'error');
            return;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Generic exception during save', ['mode' => $this->editing ? 'edit' : 'create', 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->dispatch('notify', title: 'Error', message: 'An unexpected error occurred. Please check the logs.', type: 'error');
            return;
        }
    }

    public function render()
    {
        return view('livewire.payments.payment-form');
    }
}
