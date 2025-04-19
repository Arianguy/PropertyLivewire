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

        <!-- Table Section -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cheque No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bank</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Balance</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($cheques as $cheque)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $cheque->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $cheque->contract->tenant->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $cheque->contract->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $cheque->contract->property->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $cheque->cheque_no }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $cheque->cheque_bank }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $cheque->cheque_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ number_format($cheque->amount, 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex items-center space-x-4">
                                        @if($cheque->hasAttachment())
                                            <button
                                                wire:click="$dispatch('openAttachment', { receiptId: {{ $cheque->id }} })"
                                                class="text-blue-600 hover:text-blue-800 transition-colors duration-200"
                                                title="View Cheque"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </button>
                                        @endif
                                        <button
                                            wire:click.prevent="showClearChequeModal({{ $cheque->id }})"
                                            class="text-green-600 hover:text-green-800 transition-colors duration-200"
                                            title="Clear Cheque"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500 text-sm">
                                    No pending cheques found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($cheques->hasPages())
                <div class="border-t border-gray-200">
                    {{ $cheques->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Clear Cheque Modal -->
    <div x-data="{ show: @entangle('showClearModal') }"
         x-cloak
         @keydown.escape.window="show = false">
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
                     @click="show = false"
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
                        <div class="">
                            <div class="text-center sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4" id="modal-title">Clear Cheque</h3>
                                <div class="mt-2 space-y-4">
                                    <!-- Deposit Date -->
                                    <div>
                                        <label for="depositDate" class="block text-sm font-medium text-gray-700">Deposit Date</label>
                                        <input type="date" wire:model="depositDate" id="depositDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                        @error('depositDate') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Deposit Account (Readonly) -->
                                    <div>
                                        <label for="depositAccountDisplay" class="block text-sm font-medium text-gray-700">Deposit Account</label>
                                        <input type="text" id="depositAccountDisplay" value="019100503669" readonly class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm sm:text-sm text-gray-500 cursor-not-allowed focus:ring-0 focus:border-gray-300 py-2.5 px-3">
                                    </div>
                                    <!-- Cheque Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">Cheque Status</label>
                                        <select wire:model.live="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-3">
                                            <option value="">Select Status</option>
                                            <option value="CLEARED">Cleared</option>
                                            <option value="BOUNCED">Bounced</option>
                                        </select>
                                        @error('status') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                    <!-- Remarks -->
                                    <div>
                                        <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                                        <textarea wire:model="remarks" id="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3" placeholder="Add any relevant remarks..."></textarea>
                                        @error('remarks') <span class="mt-1 text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal Footer Buttons -->
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm">
                                Submit
                            </button>
                            <button type="button" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
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
</div>
