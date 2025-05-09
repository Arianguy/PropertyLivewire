<?php

namespace App\Livewire;

use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ChequeReportMail;
use Illuminate\Support\Facades\Mail;

class ChequeManagement extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $selectedCheque = null;
    public $showClearModal = false;
    // Properties for the Clear Cheque Modal
    public $clear_depositDate;
    public $clear_status = '';
    public $clear_remarks = '';

    public $showImageModal = false;
    public $attachmentUrl = null;
    public $attachmentName = null;
    public $chequeImage = null;

    // New properties for search and date filters
    public $search = '';
    public $maturityStartDate = null;
    public $maturityEndDate = null;

    protected $paginationTheme = 'tailwind';

    // Listen for the event emitted by ResolveBouncedReceipt
    protected $listeners = ['receiptsUpdated' => '$refresh'];

    // Note: Validation rules key names must match the public properties
    protected function rules()
    {
        return [
            'clear_depositDate' => 'required|date',
            'clear_status' => 'required|in:CLEARED,BOUNCED',
            'clear_remarks' => 'required_if:clear_status,BOUNCED|nullable|string|max:1000',
            'chequeImage' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf', // For image upload modal if used
        ];
    }

    public function mount()
    {
        $this->clear_depositDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingMaturityStartDate()
    {
        $this->resetPage();
    }
    public function updatingMaturityEndDate()
    {
        $this->resetPage();
    }

    public function clearDates()
    {
        $this->maturityStartDate = null;
        $this->maturityEndDate = null;
        $this->resetPage();
    }

    private function getFilteredChequesQuery()
    {
        return Receipt::where('payment_type', 'CHEQUE')
            ->where(function ($query) {
                $query->where('status', 'PENDING')
                    ->orWhere(function ($q) {
                        $q->where('status', 'BOUNCED')
                            ->whereRaw('receipts.amount > (SELECT COALESCE(SUM(amount), 0) FROM receipts as res WHERE res.resolves_receipt_id = receipts.id)');
                    });
            })
            ->with(['contract.tenant', 'contract.property', 'resolutionReceipts'])
            ->withSum('resolutionReceipts', 'amount')
            ->when($this->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('cheque_no', 'like', '%' . $search . '%')
                        ->orWhere('cheque_bank', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%')
                        ->orWhereHas('contract.tenant', function ($tenantQuery) use ($search) {
                            $tenantQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('contract.property', function ($propertyQuery) use ($search) {
                            $propertyQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('contract', function ($contractQuery) use ($search) {
                            $contractQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($this->maturityStartDate, function ($query, $date) {
                $query->whereDate('cheque_date', '>=', $date);
            })
            ->when($this->maturityEndDate, function ($query, $date) {
                $query->whereDate('cheque_date', '<=', $date);
            })
            ->orderBy('cheque_date', 'asc');
    }

    public function render()
    {
        $query = $this->getFilteredChequesQuery();
        $cheques = $query->paginate(10);

        $grandTotals = [
            'count' => $query->count(), // Get total count before pagination for grand total
            'amount' => $query->sum('amount') // Get total sum before pagination for grand total
        ];

        return view('livewire.cheque-management', [
            'cheques' => $cheques,
            'grandTotals' => $grandTotals
        ]);
    }

    public function showClearChequeModal($chequeId)
    {
        Log::info("Attempting to open clear modal for Cheque ID: {$chequeId}");
        try {
            $this->selectedCheque = Receipt::findOrFail($chequeId);
            $this->reset(['clear_status', 'clear_remarks']);
            $this->clear_depositDate = now()->format('Y-m-d');
            $this->showClearModal = true;
            Log::info("showClearModal property set to true for Cheque ID: {$chequeId}");
        } catch (\Exception $e) {
            Log::error('Error opening clear cheque modal', ['error' => $e->getMessage()]);
            $this->dispatch('notify', [
                'message' => 'Error preparing cheque clearing. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function clearCheque()
    {
        $validatedData = $this->validate();
        Log::info('Clear Cheque Validation Passed', $validatedData);

        try {
            if (!$this->selectedCheque) {
                throw new \Exception('No cheque selected to clear.');
            }

            Log::info('Updating cheque status', [
                'cheque_id' => $this->selectedCheque->id,
                'data' => $validatedData
            ]);

            $this->selectedCheque->update([
                'deposit_date' => $validatedData['clear_depositDate'],
                'deposit_account' => '019100503669',
                'status' => $validatedData['clear_status'],
                'remarks' => $validatedData['clear_remarks'],
            ]);

            Log::info('Cheque update successful', ['cheque_id' => $this->selectedCheque->id]);

            $this->reset(['selectedCheque', 'clear_status', 'clear_remarks', 'showClearModal']);
            $this->clear_depositDate = now()->format('Y-m-d');

            $this->dispatch('notify', [
                'message' => 'Cheque status updated successfully!',
                'type' => 'success'
            ]);
            $this->dispatch('receiptsUpdated');
        } catch (\Exception $e) {
            Log::error('Error clearing cheque', ['error' => $e->getMessage()]);
            $this->dispatch('notify', [
                'message' => $e->getMessage() ?: 'Error updating cheque status. Please try again.',
                'type' => 'error'
            ]);
        }
    }

    public function viewChequeImage($chequeId)
    {
        try {
            $this->selectedCheque = Receipt::findOrFail($chequeId);
            $this->attachmentUrl = null;
            $this->attachmentName = null;

            if ($this->selectedCheque->hasChequeImage()) {
                $media = $this->selectedCheque->getFirstMedia('cheque_images');
                $this->attachmentUrl = $media->getUrl();
                $this->attachmentName = $media->name ?? 'Cheque Image';
            }

            $this->showImageModal = true;
        } catch (\Exception $e) {
            Log::error('Error viewing cheque image', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            $this->dispatch('notify', [
                'message' => 'Error viewing cheque image: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function updatedChequeImage()
    {
        $this->validate([
            'chequeImage' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        try {
            if (!$this->selectedCheque) {
                throw new \Exception('No cheque selected for image upload');
            }

            $media = $this->selectedCheque
                ->addMedia($this->chequeImage->getRealPath())
                ->usingName('Cheque_' . $this->selectedCheque->cheque_no)
                ->usingFileName('cheque_' . $this->selectedCheque->id . '_' . time() . '.' . $this->chequeImage->getClientOriginalExtension())
                ->toMediaCollection('cheque_images');

            $this->attachmentUrl = $media->getUrl();
            $this->attachmentName = $media->name;

            $this->reset('chequeImage');

            $this->dispatch('notify', [
                'message' => 'Cheque image uploaded successfully!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading cheque image', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            $this->dispatch('notify', [
                'message' => 'Error uploading cheque image: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function downloadAttachment()
    {
        if (!$this->selectedCheque || !$this->selectedCheque->hasChequeImage()) {
            return;
        }

        $media = $this->selectedCheque->getFirstMedia('cheque_images');
        return response()->download($media->getPath(), $media->name);
    }

    public function exportPdf()
    {
        $cheques = $this->getFilteredChequesQuery()->get(); // Get all filtered data, not paginated
        $userName = Auth::user() ? Auth::user()->name : 'System';

        if ($cheques->isEmpty()) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'No data available to export for the selected criteria.']);
            return;
        }

        $grandTotals = [
            'count' => $cheques->count(),
            'amount' => $cheques->sum('amount')
        ];

        $data = [
            'cheques' => $cheques,
            'search' => $this->search,
            'maturityStartDate' => $this->maturityStartDate,
            'maturityEndDate' => $this->maturityEndDate,
            'generatedAt' => now(),
            'generatedBy' => $userName,
            'grandTotals' => $grandTotals
        ];

        // Ensure the PDF view exists: resources/views/pdfs/reports/cheque-management.blade.php
        $pdf = Pdf::loadView('pdfs.reports.cheque-management', $data);
        $pdf->setPaper('a4', 'landscape');
        $filename = 'cheque-management-report-' . now()->format('Y-m-d-His') . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function emailReport()
    {
        $user = Auth::user();
        if (!$user || !$user->email) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Your email address is not configured.']);
            return;
        }

        try {
            // Ensure the ChequeReportMail Mailable exists and is configured.
            // It should accept $search, $startDate, $endDate and generate the PDF itself.
            // Mail::to($user->email)->send(new ChequeReportMail(
            //     $this->search,
            //     $this->maturityStartDate,
            //     $this->maturityEndDate,
            //     $user->id
            // ));
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Email functionality to be implemented with ChequeReportMail.']); // Placeholder notification
        } catch (\Exception $e) {
            Log::error('Error queuing cheque report email: ' . $e->getMessage(), ['exception' => $e]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Could not queue the email. Please try again.']);
        }
    }
}
