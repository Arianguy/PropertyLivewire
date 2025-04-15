<?php

namespace App\Livewire\Receipts;

use App\Models\Contract;
use Livewire\Component;

class Create extends Component
{
    public $contract;

    public function mount(Contract $contract)
    {
        // Validate that the contract is active
        if ($contract->validity !== 'YES') {
            session()->flash('error', 'Receipts can only be created for active contracts.');
            return redirect()->route('receipts.index');
        }

        $this->contract = $contract;
    }

    public function render()
    {
        return view('livewire.receipts.create');
    }
}
