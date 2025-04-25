<div>
    {{-- The Master doesn't talk, he acts. --}}

    <div class="mx-auto max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Settle Security Deposit for Contract #{{ $contract->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Record the return and any deductions for the security deposit.
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('contracts.show', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                    Back to Contract
                </a>
            </div>
        </div>

        @if($settlementExists)
            <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/30 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400 dark:text-blue-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 flex-1 md:flex md:justify-between">
                        <p class="text-sm text-blue-700 dark:text-blue-200">This contract's security deposit has already been settled on {{ $existingSettlement->created_at->format('M d, Y') }}.</p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="saveSettlement">
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Settlement Details</h3>
                </div>
                <div class="p-6 space-y-6">
                    {{-- Original Deposit --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Original Security Deposit Received</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">${{ number_format($originalDepositAmount, 2) }}</p>
                        <input type="hidden" wire:model="originalDepositAmount"> {{-- Keep it in state --}}
                    </div>

                    {{-- Deduction Amount --}}
                    <div>
                        <label for="deduction_amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Deduction Amount</label>
                        <div class="mt-2 rounded-md shadow-sm">
                            <input type="number" wire:model.lazy="deduction_amount" id="deduction_amount" step="0.01" min="0" :max="{{ $originalDepositAmount }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 disabled:opacity-50" placeholder="0.00" @if($settlementExists) disabled @endif>
                        </div>
                        @error('deduction_amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Deduction Reason --}}
                    <div x-data="{ needed: @entangle('deduction_amount').live > 0 }" x-show="needed" style="display: none;">
                        <label for="deduction_reason" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Deduction Reason</label>
                        <div class="mt-2">
                            <textarea wire:model="deduction_reason" id="deduction_reason" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 disabled:opacity-50" placeholder="Enter reason for deductions (damages, maintenance, etc.)" @if($settlementExists) disabled @endif></textarea>
                        </div>
                        @error('deduction_reason') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Return Amount (Calculated) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Final Return Amount</label>
                        <p class="mt-1 text-lg font-bold text-green-600 dark:text-green-400">${{ number_format($return_amount, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                 <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Return Details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                    {{-- Return Date --}}
                    <div>
                        <label for="return_date" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Return Date</label>
                        <div class="mt-2">
                            <input type="date" wire:model="return_date" id="return_date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 disabled:opacity-50" @if($settlementExists) disabled @endif>
                        </div>
                        @error('return_date') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Return Payment Type --}}
                    <div>
                        <label for="return_payment_type" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Return Payment Type</label>
                        <div class="mt-2">
                             <select wire:model.live="return_payment_type" id="return_payment_type" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 disabled:opacity-50" @if($settlementExists) disabled @endif>
                                <option value="CASH">Cash</option>
                                <option value="CHEQUE">Cheque</option>
                                <option value="ONLINE_TRANSFER">Online Transfer</option>
                            </select>
                        </div>
                        @error('return_payment_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Return Reference --}}
                    <div x-data="{ needed: @entangle('return_payment_type').live === 'CHEQUE' || @entangle('return_payment_type').live === 'ONLINE_TRANSFER' }" x-show="needed" style="display: none;">
                        <label for="return_reference" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Reference (Cheque # / Txn ID)</label>
                        <div class="mt-2">
                            <input type="text" wire:model="return_reference" id="return_reference" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 disabled:opacity-50" placeholder="Enter Cheque No or Transaction ID" @if($settlementExists) disabled @endif>
                        </div>
                        @error('return_reference') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                     {{-- Notes --}}
                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Notes</label>
                        <div class="mt-2">
                            <textarea wire:model="notes" id="notes" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 disabled:opacity-50" placeholder="Optional notes about the settlement" @if($settlementExists) disabled @endif></textarea>
                        </div>
                        @error('notes') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('contracts.show', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                    Cancel
                </a>
                @unless($settlementExists)
                <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                    <flux:icon name="check-circle" class="-ml-0.5 h-5 w-5" />
                    Save Settlement
                </button>
                @endunless
            </div>
        </form>
    </div>
</div>
