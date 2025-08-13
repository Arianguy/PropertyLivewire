@php
    use Illuminate\Support\Str;
@endphp

<!-- Page Container -->
<div class="py-6">
    <div class="mx-auto px-4 sm:px-6 md:px-8">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-800 sm:text-2xl sm:truncate">
                    Cheque Management
                </h2>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-4 flex flex-col md:flex-row md:items-center md:space-x-3 space-y-3 md:space-y-0 mb-6">
            <!-- Search Box -->
            <div class="md:w-96 relative rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:placeholder:text-gray-500" placeholder="Search Cheque No, Bank, Tenant, Property, Contract, Amount...">
            </div>

            <!-- Date Filters & Action Buttons -->
            <div class="flex flex-grow items-center justify-between space-x-2 md:justify-end">
                <div class="flex items-center space-x-2">
                    <label for="maturityStartDate" class="text-sm font-medium text-gray-700 dark:text-gray-300">Maturity:</label>
                    <input type="date" wire:model.live="maturityStartDate" id="maturityStartDate" class="block w-auto rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:[color-scheme:dark]">
                    <span class="text-gray-500 dark:text-gray-400">to</span>
                    <input type="date" wire:model.live="maturityEndDate" id="maturityEndDate" class="block w-auto rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:[color-scheme:dark]">
                    <button wire:click="clearDates" type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1.5 rounded focus:outline-none focus:ring-2 focus:ring-primary-500" title="Clear Dates">
                        <flux:icon name="arrow-uturn-left" class="h-4 w-4"/>
                    </button>
                </div>

                <!-- Email and Print Buttons -->
                <div class="flex items-center space-x-2">
                    <button wire:click="emailReport" type="button" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500 p-1.5 rounded focus:outline-none focus:ring-2 focus:ring-primary-500" title="Email Report">
                        <span wire:loading wire:target="emailReport" class="animate-spin inline-block h-5 w-5"><flux:icon name="arrow-path"/></span>
                        <span wire:loading.remove wire:target="emailReport"><flux:icon name="envelope" class="h-5 w-5"/></span>
                    </button>
                    <button wire:click="exportPdf" type="button" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500 p-1.5 rounded focus:outline-none focus:ring-2 focus:ring-primary-500" title="Print/Download PDF Report">
                        <span wire:loading wire:target="exportPdf" class="animate-spin inline-block h-5 w-5"><flux:icon name="arrow-path"/></span>
                        <span wire:loading.remove wire:target="exportPdf"><flux:icon name="printer" class="h-5 w-5"/></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="mt-6 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">ID</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Tenant</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Contract</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Property</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Cheque No</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Bank</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Maturity Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">Amount</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Status</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Days Balance</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                @forelse ($cheques as $cheque)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">
                                            {{ $cheque->id }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cheque->contract->tenant->name }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cheque->contract->name }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cheque->contract->property->name }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cheque->cheque_no }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cheque->cheque_bank }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $cheque->cheque_date->format('d M Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-right">
                                            {{ number_format($cheque->amount, 2) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cheque->status === 'PENDING' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' : ($cheque->status === 'BOUNCED' ? 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100') }}">
                                                {{ ucfirst(strtolower($cheque->status)) }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            @php
                                                $daysDiff = now()->diffInDays($cheque->cheque_date, false);
                                            @endphp
                                            @if($daysDiff < 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ round(abs($daysDiff)) }} days overdue
                                                </span>
                                            @elseif($daysDiff == 0)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Due today
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ round($daysDiff) }} days left
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">
                                            <div class="flex items-center space-x-4">
                                                @if($cheque->hasAttachment())
                                                    <button
                                                        wire:click="$dispatch('openAttachment', { receiptId: {{ $cheque->id }} })"
                                                        class="text-blue-600 hover:text-blue-800 transition-colors duration-200 dark:text-blue-400 dark:hover:text-blue-300"
                                                        title="View Cheque"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                                <button
                                                    wire:click.prevent="showClearChequeModal({{ $cheque->id }})"
                                                    class="text-green-600 hover:text-green-800 transition-colors duration-200 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Update Status (Clear/Bounce)"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>

                                                {{-- Resolve Button only appears for BOUNCED cheques (which are already filtered to be unresolved by the component query) --}}
                                                {{-- The component query ensures only PENDING or UNRESOLVED BOUNCED cheques are shown --}}
                                                {{-- So we only need to check if the status is BOUNCED here --}}
                                                @if($cheque->status === 'BOUNCED')
                                                    {{-- Calculate and Display Remaining Amount --}}
                                                    @php
                                                        $totalResolved = $cheque->resolution_receipts_sum_amount ?? 0;
                                                        $remainingAmount = $cheque->amount - $totalResolved;
                                                    @endphp
                                                    @if($remainingAmount > 0)
                                                        <span class="text-xs text-orange-600 dark:text-orange-400 mr-2">({{ number_format($remainingAmount, 2) }} Due)</span>
                                                    @endif

                                                    {{-- Record Resolution Button --}}
                                                    <button
                                                        wire:click="$dispatch('openResolveModal', { receiptId: {{ $cheque->id }} })"
                                                        class="text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 transition-colors duration-200"
                                                        title="Record Resolution Payment">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16Zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                            No cheques found matching your criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($cheques->hasPages() || (isset($grandTotals) && $grandTotals['count'] > 0) )
                            <tfoot class="bg-gray-50 dark:bg-gray-800">
                                @if (isset($grandTotals) && $grandTotals['count'] > 0)
                                <tr class="font-semibold text-gray-900 dark:text-gray-100">
                                    <td colspan="7" class="py-3.5 pl-4 pr-3 text-left text-sm sm:pl-6">Grand Totals ({{ $grandTotals['count'] }} Cheques):</td>
                                    <td class="px-3 py-3.5 text-right text-sm">{{ number_format($grandTotals['amount'], 2) }}</td>
                                    <td colspan="3" class="px-3 py-3.5 text-left text-sm"></td> {{-- Empty cells for remaining columns --}}
                                </tr>
                                @endif
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    @if($cheques->hasPages())
                        <div class="py-3 px-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $cheques->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Clear Cheque Modal -->
    <div x-data="{ show: @entangle('showClearModal') }"
         x-cloak
         @keydown.escape.window="$wire.showClearModal = false"
         @close-modal.window="$wire.showClearModal = false">
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="show"
                     @click="$wire.showClearModal = false"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-25 transition-opacity"
                     aria-hidden="true">
                </div>

                <!-- Modal panel -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="show"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <form wire:submit.prevent="clearCheque">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Update Cheque Status
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Update status for Cheque #{{ $selectedCheque?->cheque_no }}</p>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="clear_depositDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deposit/Clear Date</label>
                                        <input type="date" wire:model="clear_depositDate" id="clear_depositDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:[color-scheme:dark] py-2.5 px-3">
                                        @error('clear_depositDate') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="clear_depositAccount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deposit Account</label>
                                        <input type="text" value="019100503669" id="clear_depositAccount" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 py-2.5 px-3">
                                    </div>
                                    <div>
                                        <label for="clear_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                        <select wire:model="clear_status" id="clear_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 py-2.5 px-3">
                                            <option value="">Select Status</option>
                                            <option value="CLEARED">Cleared</option>
                                            <option value="BOUNCED">Bounced</option>
                                        </select>
                                        @error('clear_status') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="clear_remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                                        <textarea wire:model="clear_remarks" id="clear_remarks" rows="3" placeholder="Enter reason if bounced, or other notes..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 py-2.5 px-3"></textarea>
                                        @error('clear_remarks') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm dark:bg-indigo-500 dark:hover:bg-indigo-600">
                                Update Status
                            </button>
                            <button type="button" @click="$wire.showClearModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the ViewAttachment component to handle attachment viewing -->
    @livewire('receipts.view-attachment')

    <!-- Include the ResolveBouncedReceipt component -->
    @livewire('receipts.resolve-bounced-receipt')
</div>
