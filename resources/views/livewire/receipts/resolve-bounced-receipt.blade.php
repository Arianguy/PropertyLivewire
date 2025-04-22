<div
    x-data="{ show: @entangle('showModal') }"
    x-show="show"
    x-cloak
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity dark:bg-gray-900 dark:bg-opacity-75"
             @click="show = false" <!-- Close modal on overlay click -->

        </div>

        <!-- Centered modal content -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 dark:bg-gray-800">

            <form wire:submit.prevent="saveResolution">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                            Record Resolution Payment
                        </h3>
                        @if($bouncedReceipt)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                For Bounced Cheque #{{ $bouncedReceipt->cheque_no }}
                                (Amount: {{ number_format($bouncedReceipt->amount, 2) }})
                            </p>
                        @endif
                        <div class="mt-4 space-y-4">
                            <!-- Payment Type -->
                            <div>
                                <label for="resolve_payment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Type</label>
                                <select wire:model.live="payment_type" id="resolve_payment_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 py-2.5 px-3">
                                    <option value="CASH">Cash</option>
                                    <option value="ONLINE_TRANSFER">Online Transfer</option>
                                </select>
                                @error('payment_type') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <!-- Amount -->
                            <div>
                                <label for="resolve_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount Received</label>
                                <input type="number" step="0.01" wire:model="amount" id="resolve_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 py-2.5 px-3">
                                @error('amount') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <!-- Receipt Date -->
                            <div>
                                <label for="resolve_receipt_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Date</label>
                                <input type="date" wire:model="receipt_date" id="resolve_receipt_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:[color-scheme:dark] py-2.5 px-3">
                                @error('receipt_date') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>

                            <!-- Transaction Reference (Online Transfer Only) -->
                            @if($payment_type === 'ONLINE_TRANSFER')
                            <div>
                                <label for="resolve_transaction_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Reference</label>
                                <input type="text" wire:model="transaction_reference" id="resolve_transaction_reference" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 py-2.5 px-3">
                                @error('transaction_reference') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            <!-- Narration -->
                            <div>
                                <label for="resolve_narration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Narration</label>
                                <textarea wire:model="narration" id="resolve_narration" rows="3" placeholder="Payment for bounced cheque..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 py-2.5 px-3"></textarea>
                                @error('narration') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm dark:bg-green-500 dark:hover:bg-green-600">
                        Save Resolution
                    </button>
                    <button type="button" wire:click="closeModal" @click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
