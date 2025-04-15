<?php

namespace App\Livewire\Receipts;

use App\Models\Contract;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $contracts = Contract::with('tenant', 'property')
            ->where('validity', 'YES')
            ->get();

        return view('livewire.receipts.index', [
            'contracts' => $contracts
        ]);
    }
}
