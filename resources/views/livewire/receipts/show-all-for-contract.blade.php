<div class="w-full">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex-grow text-center">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                Receipts for Contract:
                <span class="ml-2 inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-primary-100 text-primary-800 dark:bg-primary-700 dark:text-primary-200">
                    {{ $contract->name }}
                </span>
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Browse and manage all receipts associated with contract #{{ $contract->name }}.
            </p>
        </div>
        <div>
            <a href="{{ route('receipts.index') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                Back to Contracts List
            </a>
        </div>
    </div>

    {{-- Original table content starts here --}}
    <div class="mt-6 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    {{-- This is where the original table from the file will be placed --}}
                    {{-- Keeping the existing table structure as it was --}}
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('receipt_category')">
                                    <div class="flex items-center">
                                        Category
                                        @if($sortField === 'receipt_category')
                                            <span class="ml-1">
                                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                            </span>
                                        @endif
                                    </div>
                                </th>
                                <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('cheque_no')">
                                    <div class="flex items-center">
                                        Cheque No
                                        @if($sortField === 'cheque_no')
                                            <span class="ml-1">
                                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                            </span>
                                        @endif
                                    </div>
                                </th>
                                <th class="py-3 px-6 text-left">Amount</th>
                                <th class="py-3 px-6 text-left">Payment Type</th>
                                <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('receipt_date')">
                                    <div class="flex items-center">
                                        Date
                                        @if($sortField === 'receipt_date' || $sortField === 'cheque_date')
                                            <span class="ml-1">
                                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                            </span>
                                        @endif
                                    </div>
                                </th>
                                <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('status')">
                                    <div class="flex items-center">
                                        Status
                                        @if($sortField === 'status')
                                            <span class="ml-1">
                                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                            </span>
                                        @endif
                                    </div>
                                </th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-400 text-sm">
                            @forelse($receipts as $receipt)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <td class="py-3 px-6 text-left text-gray-900 dark:text-gray-100">{{ $receipt->receipt_category }}</td>
                                <td class="py-3 px-6 text-left text-gray-900 dark:text-gray-100">{{ $receipt->cheque_no ?? '-' }}</td>
                                <td class="py-3 px-6 text-left text-gray-900 dark:text-gray-100">{{ number_format($receipt->amount, 2) }}</td>
                                <td class="py-3 px-6 text-left">
                                    <span>{{ $receipt->payment_type }}</span>
                                    @if($receipt->resolvedReceipt)
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 italic mt-1">
                                            (Agst Ch: #{{ $receipt->resolvedReceipt->cheque_no ?? 'N/A' }})
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left">
                                    @if ($receipt->payment_type === 'CHEQUE' && !is_null($receipt->deposit_date))
                                        {{ $receipt->deposit_date ? \Carbon\Carbon::parse($receipt->deposit_date)->format('d M Y') : 'N/A' }}
                                    @elseif ($receipt->payment_type === 'CHEQUE' && is_null($receipt->deposit_date))
                                        {{ $receipt->cheque_date ? \Carbon\Carbon::parse($receipt->cheque_date)->format('d M Y') : 'N/A' }} (Cheque Date)
                                    @else
                                        {{ $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->format('d M Y') : 'N/A' }}
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-left">
                                    @php
                                        $statusClass = match(strtoupper($receipt->status ?? '')) {
                                            'CLEARED' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-900/50 dark:text-green-400',
                                            'BOUNCED' => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/50 dark:text-red-400',
                                            'PENDING' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-900/50 dark:text-yellow-400',
                                            'CANCELLED' => 'bg-gray-100 text-gray-600 ring-gray-500/20 dark:bg-gray-700 dark:text-gray-300',
                                            default => 'bg-gray-100 text-gray-600 ring-gray-500/20 dark:bg-gray-700 dark:text-gray-300',
                                        };
                                        $statusText = $receipt->status;
                                        if ($receipt->status === 'BOUNCED' && $receipt->resolutionReceipts->isNotEmpty()) {
                                            $statusText .= ' (Resolved)';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                    @if($receipt->payment_type === 'CHEQUE' && $receipt->status === 'PENDING' && $receipt->cheque_date)
                                        @php
                                            $chequeDate = \Carbon\Carbon::parse($receipt->cheque_date)->startOfDay();
                                            $today = \Carbon\Carbon::now()->startOfDay();
                                            $daysDiff = $today->diffInDays($chequeDate, false);
                                        @endphp
                                        <span @class([
                                            'ml-2 text-xs font-medium',
                                            'text-red-600 dark:text-red-400' => $daysDiff < 0,
                                            'text-yellow-600 dark:text-yellow-400' => $daysDiff == 0,
                                            'text-green-600 dark:text-green-400' => $daysDiff > 0,
                                        ])>
                                            @if($daysDiff < 0) ({{ abs($daysDiff) }} days Late)
                                            @elseif($daysDiff == 0) (Due Today)
                                            @else ({{ $daysDiff }} days)
                                            @endif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        @if($receipt->payment_type !== 'CASH' && $receipt->hasAttachment())
                                            <button wire:click="$dispatch('openAttachment', { receiptId: {{ $receipt->id }} })" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors duration-200" title="View Attachment">
                                                <flux:icon name="document-text" class="h-5 w-5" /> {{-- Using document-text for attachment --}}
                                            </button>
                                        @endif
                                        @if($receipt->status === 'BOUNCED')
                                            @php
                                                $totalResolved = $receipt->resolution_receipts_sum_amount ?? 0;
                                                $isFullyResolved = $totalResolved >= $receipt->amount;
                                            @endphp
                                            @if(!$isFullyResolved)
                                                <button wire:click="$dispatch('openResolveModal', { receiptId: {{ $receipt->id }} })" class="text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 transition-colors duration-200" title="Record Resolution Payment">
                                                    <flux:icon name="plus-circle" class="h-5 w-5" /> {{-- Using plus-circle for resolve --}}
                                                </button>
                                            @endif
                                        @endif
                                        <a href="{{ route('receipts.edit', $receipt->id) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 transition-colors duration-200" title="Edit Receipt">
                                            <flux:icon name="pencil-square" class="h-5 w-5" />
                                        </a>
                                        <form action="{{ route('receipts.destroy', $receipt->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this receipt?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors duration-200" title="Delete Receipt">
                                                <flux:icon name="trash" class="h-5 w-5" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-3 px-6 text-center text-gray-500 dark:text-gray-400">No receipts found for this contract.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> {{-- End of overflow-hidden shadow --}}
            </div> {{-- End of inline-block min-w-full --}}
        </div> {{-- End of -mx-4 -my-2 --}}
    </div> {{-- End of mt-6 flow-root --}}

    @if ($receipts->hasPages())
    <div class="mt-4 p-2 bg-gray-50 dark:bg-gray-700 rounded">
        {{ $receipts->links() }}
    </div>
    @endif

    {{-- Modals included from original file --}}
    @livewire('receipts.view-attachment')
    @livewire('receipts.resolve-bounced-receipt')
</div>
