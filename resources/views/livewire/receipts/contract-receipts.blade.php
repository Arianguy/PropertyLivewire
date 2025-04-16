<div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('receipt_category')">
                        <div class="flex items-center">
                            Category
                            @if($sortField === 'receipt_category')
                                <span class="ml-1">
                                    @if($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
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
                                    @if($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                </span>
                            @endif
                        </div>
                    </th>
                    <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('status')">
                        <div class="flex items-center">
                            Status
                            @if($sortField === 'status')
                                <span class="ml-1">
                                    @if($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                </span>
                            @endif
                        </div>
                    </th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @forelse($receipts as $receipt)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-6 text-left">{{ $receipt->receipt_category }}</td>
                    <td class="py-3 px-6 text-left">{{ number_format($receipt->amount, 2) }}</td>
                    <td class="py-3 px-6 text-left">{{ $receipt->payment_type }}</td>
                    <td class="py-3 px-6 text-left">
                        @if($receipt->payment_type === 'CHEQUE')
                            {{ $receipt->cheque_date ? \Carbon\Carbon::parse($receipt->cheque_date)->format('d M Y') : 'N/A' }}
                        @else
                            {{ $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->format('d M Y') : 'N/A' }}
                        @endif
                    </td>
                    <td class="py-3 px-6 text-left">
                        <span class="px-2 py-1 rounded-full text-xs
                            @if($receipt->status === 'CLEARED') bg-green-200 text-green-800
                            @elseif($receipt->status === 'BOUNCED') bg-red-200 text-red-800
                            @else bg-yellow-200 text-yellow-800 @endif">
                            {{ $receipt->status }}
                        </span>

                        @if($receipt->payment_type === 'CHEQUE' && $receipt->status === 'PENDING' && $receipt->cheque_date)
                            @php
                                $chequeDate = \Carbon\Carbon::parse($receipt->cheque_date)->startOfDay();
                                $today = \Carbon\Carbon::now()->startOfDay();
                                $daysDiff = $today->diffInDays($chequeDate, false);
                            @endphp
                            <span @class([
                                'ml-2 text-xs font-medium',
                                'text-red-600' => $daysDiff < 0,
                                'text-yellow-600' => $daysDiff == 0,
                                'text-green-600' => $daysDiff > 0,
                            ])>
                                @if($daysDiff < 0)
                                    ({{ abs($daysDiff) }} days Late)
                                @elseif($daysDiff == 0)
                                    (Due Today)
                                @else
                                    ({{ $daysDiff }} days)
                                @endif
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            @if($receipt->payment_type !== 'CASH' && $receipt->hasAttachment())
                                <button
                                    wire:click="$dispatch('openAttachment', { receiptId: {{ $receipt->id }} })"
                                    class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                    title="View Attachment">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </button>
                            @endif

                            <a
                                href="{{ route('receipts.edit', $receipt->id) }}"
                                class="text-green-600 hover:text-green-800 transition-colors duration-200"
                                title="Edit Receipt">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <form action="{{ route('receipts.destroy', $receipt->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this receipt?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-200" title="Delete Receipt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-3 px-6 text-center">No receipts found for this contract</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($receipts->hasPages())
    <div class="mt-4">
        {{ $receipts->links() }}
    </div>
    @endif

    <!-- Include the ViewAttachment component to handle attachment viewing -->
    @livewire('receipts.view-attachment')
</div>
