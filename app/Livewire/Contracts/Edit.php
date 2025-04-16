<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    use WithFileUploads;

    public Contract $contract;
    public $tenant_id;
    public $property_id;
    public $cstart;
    public $cend;
    public $amount;
    public $sec_amt;
    public $ejari;
    public $cont_copy;
    public $media = [];

    protected function rules()
    {
        return [
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'cstart' => 'required|date',
            'cend' => 'required|date|after:cstart',
            'amount' => 'required|numeric|min:0',
            'sec_amt' => 'required|numeric|min:0',
            'ejari' => 'nullable|string',
            'cont_copy.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
        $this->tenant_id = $contract->tenant_id;
        $this->property_id = $contract->property_id;
        $this->cstart = $contract->cstart->format('Y-m-d');
        $this->cend = $contract->cend->format('Y-m-d');
        $this->amount = $contract->amount;
        $this->sec_amt = $contract->sec_amt;
        $this->ejari = $contract->ejari;
        $this->loadMedia();
    }

    public function loadMedia()
    {
        $this->media = $this->contract->getMedia('contracts_copy')->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->file_name,
                'size' => $item->size,
                'type' => $item->mime_type,
                'url' => route('media.show', $item->id),
                'download_url' => route('media.download', $item->id),
                'thumbnail' => $item->hasGeneratedConversion('thumb')
                    ? route('media.thumbnail', ['id' => $item->id, 'conversion' => 'thumb'])
                    : null
            ];
        })->toArray();
    }

    public function deleteMedia($mediaId)
    {
        try {
            // Find the media item in the collection
            $media = $this->contract->getMedia('contracts_copy')->firstWhere('id', $mediaId);

            if ($media) {
                // Delete the media item from storage
                $mediaDeleted = $media->delete();

                if ($mediaDeleted) {
                    // Remove the item from the local array
                    $this->media = array_values(array_filter($this->media, function ($item) use ($mediaId) {
                        return $item['id'] != $mediaId;
                    }));

                    // Dispatch events
                    $this->dispatch('media-deleted');
                    $this->dispatch('notify', [
                        'type' => 'success',
                        'message' => 'File deleted successfully!'
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error deleting file: ' . $e->getMessage()
            ]);
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->contract->update([
                'tenant_id' => $this->tenant_id,
                'property_id' => $this->property_id,
                'cstart' => $this->cstart,
                'cend' => $this->cend,
                'amount' => $this->amount,
                'sec_amt' => $this->sec_amt,
                'ejari' => $this->ejari,
            ]);

            if ($this->cont_copy) {
                foreach ($this->cont_copy as $file) {
                    $this->contract->addMedia($file->getRealPath())
                        ->usingName($file->getClientOriginalName())
                        ->usingFileName($file->getClientOriginalName())
                        ->toMediaCollection('contracts_copy');
                }

                $this->cont_copy = null;
                $this->loadMedia();
            }

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Contract updated successfully!'
            ]);

            return redirect()->route('contracts.table');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error updating contract: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.contracts.edit', [
            'tenants' => Tenant::all(),
            'properties' => Property::all(),
        ]);
    }
}
