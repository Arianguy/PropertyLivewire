<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Terminate extends Component
{
    public Contract $contract;
    public $close_date;
    public $amount;
    public $reason;

    protected function rules()
    {
        return [
            'close_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ];
    }

    public function mount(Contract $contract)
    {
        $this->contract = $contract;
        $this->close_date = now()->format('Y-m-d');
        $this->amount = $contract->amount;
    }

    public function terminate()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Update the contract with termination details
            $this->contract->update([
                'cend' => $this->close_date, // Update end date to close date
                'amount' => $this->amount, // Update final amount
                'validity' => 'NO',
                'type' => 'terminated',
                'termination_reason' => $this->reason
            ]);

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Contract terminated successfully!'
            ]);

            return redirect()->route('contracts.show', $this->contract);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error terminating contract: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.contracts.terminate');
    }
}
