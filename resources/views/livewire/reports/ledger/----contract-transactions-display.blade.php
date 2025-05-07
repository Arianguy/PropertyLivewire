<div class="overflow-x-auto bg-sky-100 dark:bg-sky-800/70 p-3 rounded-md shadow">
    <table class="min-w-full text-sm">
        <thead class="border-b-2 border-sky-300 dark:border-sky-600">
            <tr class="text-xs text-gray-700 dark:text-sky-200 uppercase">
                <th class="py-2 px-3 text-left font-semibold">Trans ID</th>
                <th class="py-2 px-3 text-left font-semibold">Purpose</th>
                <th class="py-2 px-3 text-left font-semibold">Type</th>
                <th class="py-2 px-3 text-left font-semibold">Narration</th>
                <th class="py-2 px-3 text-left font-semibold">Chq Status</th>
                <th class="py-2 px-3 text-left font-semibold">Chq No</th>
                <th class="py-2 px-3 text-left font-semibold">Bank</th>
                <th class="py-2 px-3 text-right font-semibold">Amount</th>
                <th class="py-2 px-3 text-left font-semibold">Chq Date</th>
                <th class="py-2 px-3 text-left font-semibold">Deposit On</th>
                <th class="py-2 px-3 text-left font-semibold">Deposit A/C</th>
                <th class="py-2 px-3 text-center font-semibold">View</th>
            </tr>
        </thead>
        <tbody class="text-gray-800 dark:text-gray-300">
            @forelse($transactions as $transaction)
                <tr class="border-b border-sky-200 dark:border-sky-700 hover:bg-sky-200/50 dark:hover:bg-sky-700/50 transition-colors duration-150 ease-in-out">
                    <td class="py-2.5 px-3 text-left">{{ $transaction->id }}</td>
                    <td class="py-2.5 px-3 text-left">{{ $transaction->receipt_category ?? 'N/A' }}</td>
                    <td class="py-2.5 px-3 text-left">
                        <span>{{ $transaction->payment_type ?? 'N/A' }}</span>
                        @if($transaction->resolvedReceipt) {{-- This transaction is a resolution for a bounced one --}}
                            <span class="block text-xs text-gray-500 dark:text-gray-400 italic mt-0.5">
                                (Against Chq: #{{ $transaction->resolvedReceipt->cheque_no ?? 'N/A' }})
                            </span>
                        @endif
                    </td>
                    <td class="py-2.5 px-3 text-left">{{ $transaction->narration ?? '-' }}</td>
                    <td class="py-2.5 px-3 text-left">
                        @php
                            $status = strtoupper($transaction->status ?? '');
                            $statusClass = match($status) {
                                'CLEARED' => 'bg-green-100 text-green-700 dark:bg-green-700/30 dark:text-green-300',
                                'BOUNCED' => 'bg-red-100 text-red-700 dark:bg-red-700/30 dark:text-red-300',
                                'PENDING' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-700/30 dark:text-yellow-300',
                                'CANCELLED' => 'bg-gray-200 text-gray-600 dark:bg-gray-600/30 dark:text-gray-400',
                                default => 'bg-gray-100 text-gray-500 dark:bg-gray-700/30 dark:text-gray-500',
                            };
                            $statusText = $transaction->status ?? 'N/A';
                            if ($status === 'BOUNCED' && $transaction->resolutionReceipts->isNotEmpty()) {
                                $totalResolvedAmount = $transaction->resolution_receipts_sum_amount ?? 0;
                                if ($totalResolvedAmount >= $transaction->amount) {
                                    $statusText .= ' (Resolved)';
                                } else {
                                    $statusText .= ' (Partially Resolved)'; // Or just (Resolved) if partial is not distinct
                                }
                            }
                        @endphp
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                        @if($transaction->payment_type === 'CHEQUE' && $status === 'PENDING' && $transaction->cheque_date)
                             @php
                                $chequeDateCarbon = \Carbon\Carbon::parse($transaction->cheque_date)->startOfDay();
                                $today = \Carbon\Carbon::now()->startOfDay();
                                $daysDiff = $today->diffInDays($chequeDateCarbon, false);
                            @endphp
                            <span @class([
                                'ml-1 text-xs font-semibold',
                                'text-red-500 dark:text-red-400' => $daysDiff < 0,
                                'text-yellow-600 dark:text-yellow-500' => $daysDiff == 0,
                                'text-green-600 dark:text-green-400' => $daysDiff > 0,
                            ])>
                                @if($daysDiff < 0) ({{ abs($daysDiff) }}d Late)
                                @elseif($daysDiff == 0) (Due Today)
                                @else ({{ $daysDiff }}d Left)
                                @endif
                            </span>
                        @endif
                    </td>
                    <td class="py-2.5 px-3 text-left">{{ $transaction->cheque_no ?? '-' }}</td>
                    <td class="py-2.5 px-3 text-left">{{ $transaction->payment_type === 'CHEQUE' ? ($transaction->cheque_bank ?? 'N/A') : 'N/A' }}</td>
                    <td class="py-2.5 px-3 text-right">{{ number_format($transaction->amount, 0) }}</td>
                    <td class="py-2.5 px-3 text-left">{{ $transaction->payment_type === 'CHEQUE' && $transaction->cheque_date ? \Carbon\Carbon::parse($transaction->cheque_date)->format('d-M-Y') : '-' }}</td>
                    <td class="py-2.5 px-3 text-left">{{ $transaction->deposit_date ? \Carbon\Carbon::parse($transaction->deposit_date)->format('d-M-Y') : 'No Date Available' }}</td>
                    <td class="py-2.5 px-3 text-left">{{ $transaction->payment_type === 'CHEQUE' ? ($transaction->deposit_account ?? 'N/A') : 'N/A' }}</td>
                    <td class="py-2.5 px-3 text-center">
                        <button type="button" wire:click="viewTransactionDetails({{ $transaction->id }})" class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold py-1 px-3 rounded-md shadow-sm transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75">
                            View
                        </button>
                        {{-- Add logic here if view button should show attachment or link to edit etc. --}}
                        {{-- For example, if it's about attachments: --}}
                        {{-- @if($transaction->hasMedia('attachments')) --}}
                            {{-- <button wire:click="$dispatch('openAttachmentModal', { transactionId: {{ $transaction->id }} })">View</button> --}}
                        {{-- @else --}}
                            {{-- <span class="text-xs text-gray-400">No Attachment</span> --}}
                        {{-- @endif --}}
                    </td>
                </tr>
            @empty
                <tr class="border-b border-sky-200 dark:border-sky-700">
                    <td colspan="12" class="py-4 px-3 text-center text-gray-500 dark:text-gray-400">
                        No transactions recorded for this contract.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Modal for viewing transaction details (Example Structure) --}}
    {{-- @if($selectedTransaction)
    <x-dialog-modal wire:model="showingTransactionModal">
        <x-slot name="title">
            Transaction Details (ID: {{ $selectedTransaction->id }})
        </x-slot>

        <x-slot name="content">
            <p><strong>Purpose:</strong> {{ $selectedTransaction->receipt_category }}</p>
            <p><strong>Amount:</strong> {{ number_format($selectedTransaction->amount, 0) }}</p>
            // Add more details here
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showingTransactionModal', false)">
                Close
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>
    @endif --}}
</div>
