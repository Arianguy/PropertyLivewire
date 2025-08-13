<div class="p-4">
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
    
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-base font-medium text-gray-800">Receipt Details</h2>
        <button type="button" wire:click="addReceipt" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <span class="mr-1">+</span> Add Receipt
        </button>
    </div>

    <div class="space-y-3">
        @foreach($receipts as $index => $receipt)
            <div class="bg-white border border-gray-200 rounded shadow-sm" wire:key="receipt-{{ $index }}">
                <div class="flex justify-between items-center p-3 border-b border-gray-200 bg-gray-50">
                    <span class="text-sm font-medium text-blue-700">Receipt #{{ $index + 1 }}</span>
                    @if($index > 0)
                        <button type="button" wire:click="removeReceipt({{ $index }})" class="text-red-600 hover:text-red-900 text-xs">
                            Remove
                        </button>
                    @endif
                </div>

                <div class="p-3">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <label for="payment_type_{{ $index }}" class="block text-xs font-medium text-gray-700">Payment Type</label>
                            <div class="relative mt-1">
                                <select id="payment_type_{{ $index }}" wire:model.live="receipts.{{ $index }}.payment_type"
                                        class="appearance-none block w-full pl-3 pr-10 py-1.5 text-sm border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 bg-no-repeat bg-[position:right_0.5rem_center] bg-[size:1.5em_1.5em] bg-[url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e")] dark:bg-[url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e")]">
                                    <option value="CASH">Cash</option>
                                    <option value="CHEQUE">Cheque</option>
                                    <option value="ONLINE_TRANSFER">Online Transfer</option>
                                </select>
                            </div>
                            @error("receipts.$index.payment_type") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="receipt_category_{{ $index }}" class="block text-xs font-medium text-gray-700">Category</label>
                            <div class="relative mt-1">
                                <select id="receipt_category_{{ $index }}" wire:model.live="receipts.{{ $index }}.receipt_category"
                                        class="appearance-none block w-full pl-3 pr-10 py-1.5 text-sm border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 bg-no-repeat bg-[position:right_0.5rem_center] bg-[size:1.5em_1.5em] bg-[url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e")] dark:bg-[url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e")]">
                                    <option value="RENT">Rent</option>
                                    <option value="RETURN CHEQUE">Return Cheque</option>
                                    <option value="SECURITY_DEPOSIT">Security Deposit</option>
                                    <option value="VAT">VAT</option>
                                </select>
                            </div>
                            @error("receipts.$index.receipt_category") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        @if(($receipt['receipt_category'] ?? 'RENT') !== 'VAT')
                        <div>
                            <label for="amount_{{ $index }}" class="block text-xs font-medium text-gray-700">
                                @if($receipt['payment_type'] === 'CASH')
                                    Cash Amount
                                @elseif($receipt['payment_type'] === 'CHEQUE')
                                    Cheque Amount
                                @else
                                    Transfer Amount
                                @endif
                            </label>
                            <input type="number" step="0.01" id="amount_{{ $index }}" wire:model="receipts.{{ $index }}.amount" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                            @error("receipts.$index.amount") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        @if(($receipt['receipt_category'] ?? 'RENT') === 'VAT')
                        <div>
                            <label for="vat_amount_{{ $index }}" class="block text-xs font-medium text-gray-700">VAT Amount</label>
                            <input type="number" step="0.01" id="vat_amount_{{ $index }}" wire:model="receipts.{{ $index }}.vat_amount" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                            @error("receipts.$index.vat_amount") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        @elseif($contract->isVatApplicable())
                        <div>
                            <label for="vat_rate_{{ $index }}" class="block text-xs font-medium text-gray-700">VAT Rate (%)</label>
                            <input type="number" step="0.1" id="vat_rate_{{ $index }}" wire:model="receipts.{{ $index }}.vat_rate" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" readonly>
                            @error("receipts.$index.vat_rate") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="vat_amount_{{ $index }}" class="block text-xs font-medium text-gray-700">VAT Amount</label>
                            <input type="number" step="0.01" id="vat_amount_{{ $index }}" wire:model="receipts.{{ $index }}.vat_amount" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" readonly>
                            @error("receipts.$index.vat_amount") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        @if(($receipt['receipt_category'] ?? 'RENT') !== 'VAT')
                        <div class="col-span-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="vat_inclusive_{{ $index }}" wire:model="receipts.{{ $index }}.vat_inclusive" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" {{ ($receipt['receipt_category'] ?? 'RENT') === 'RENT' ? 'disabled' : '' }}>
                                <label for="vat_inclusive_{{ $index }}" class="ml-2 block text-xs font-medium text-gray-700">
                                    Amount is VAT inclusive {{ ($receipt['receipt_category'] ?? 'RENT') === 'RENT' ? '(Always for Rent)' : '' }}
                                </label>
                            </div>
                            @error("receipts.$index.vat_inclusive") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2">
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-2">
                                <div class="text-xs text-blue-800">
                                    @if(($receipt['vat_inclusive'] ?? false))
                                        <strong>Total Amount (including VAT): {{ number_format((float)($receipt['amount'] ?? 0), 2) }}</strong>
                                        <br><span class="text-xs">Rent Amount: {{ number_format((float)($receipt['amount'] ?? 0) - (float)($receipt['vat_amount'] ?? 0), 2) }} | VAT Amount: {{ number_format((float)($receipt['vat_amount'] ?? 0), 2) }}</span>
                                    @else
                                        <strong>Total Amount (including VAT): {{ number_format((float)($receipt['amount'] ?? 0) + (float)($receipt['vat_amount'] ?? 0), 2) }}</strong>
                                        <br><span class="text-xs">Rent Amount: {{ number_format((float)($receipt['amount'] ?? 0), 2) }} | VAT Amount: {{ number_format((float)($receipt['vat_amount'] ?? 0), 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif

                        @if(($receipt['receipt_category'] ?? 'RENT') === 'VAT')
                        <div class="col-span-2">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-2">
                                <div class="text-xs text-yellow-800">
                                    <strong>Note:</strong> For VAT category, enter the VAT amount in the VAT Amount field. This will be used for all VAT-related calculations.
                                </div>
                            </div>
                        </div>
                        @endif

                        <div>
                            <label for="receipt_date_{{ $index }}" class="block text-xs font-medium text-gray-700">
                                @if($receipt['payment_type'] === 'CASH')
                                    Cash Receipt Date
                                @elseif($receipt['payment_type'] === 'ONLINE_TRANSFER')
                                    Transfer Receipt Date
                                @endif
                            </label>
                            @if($receipt['payment_type'] === 'CASH' || $receipt['payment_type'] === 'ONLINE_TRANSFER')
                            <input type="date" id="receipt_date_{{ $index }}" wire:model="receipts.{{ $index }}.receipt_date" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                            @error("receipts.$index.receipt_date") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @endif
                        </div>

                        @if($receipt['payment_type'] === 'CHEQUE')
                            <div>
                                <label for="cheque_no_{{ $index }}" class="block text-xs font-medium text-gray-700">Cheque No</label>
                                <input type="text" id="cheque_no_{{ $index }}" wire:model="receipts.{{ $index }}.cheque_no" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                                @error("receipts.$index.cheque_no") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="cheque_bank_{{ $index }}" class="block text-xs font-medium text-gray-700">Cheque Bank</label>
                                <div class="relative mt-1">
                                    <select id="cheque_bank_{{ $index }}" wire:model="receipts.{{ $index }}.cheque_bank"
                                            class="appearance-none block w-full pl-3 pr-10 py-1.5 text-sm border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 bg-no-repeat bg-[position:right_0.5rem_center] bg-[size:1.5em_1.5em] bg-[url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e")] dark:bg-[url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e")]" required>
                                        <option value="">Select Bank</option>
                                        <option value="ENBD">Emirates NBD</option>
                                        <option value="CBD">Commercial Bank of Dubai</option>
                                        <option value="FAB">First Abu Dhabi Bank</option>
                                        <option value="Mashreq Bank">Mashreq Bank</option>
                                        <option value="DIB">Dubai Islamic Bank</option>
                                        <option value="EIB">Emirates Islamic Bank</option>
                                    </select>
                                </div>
                                @error("receipts.$index.cheque_bank") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="cheque_date_{{ $index }}" class="block text-xs font-medium text-gray-700">Cheque Date</label>
                                <input type="date" id="cheque_date_{{ $index }}" wire:model="receipts.{{ $index }}.cheque_date" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                                @error("receipts.$index.cheque_date") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="cheque_image_{{ $index }}" class="block text-xs font-medium text-gray-700">Cheque Copy</label>
                                <div class="flex items-center space-x-2">
                                    <input type="file" id="cheque_image_{{ $index }}" wire:model="receipts.{{ $index }}.cheque_image" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md py-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                                    <div wire:loading wire:target="receipts.{{ $index }}.cheque_image">
                                        <span class="text-xs text-blue-500">Uploading...</span>
                                    </div>
                                </div>
                                @error("receipts.$index.cheque_image") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($receipt['payment_type'] === 'ONLINE_TRANSFER')
                            <div class="sm:col-span-2">
                                <label for="transaction_reference_{{ $index }}" class="block text-xs font-medium text-gray-700">Transaction Reference</label>
                                <input type="text" id="transaction_reference_{{ $index }}" wire:model="receipts.{{ $index }}.transaction_reference" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                                @error("receipts.$index.transaction_reference") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="transfer_receipt_image_{{ $index }}" class="block text-xs font-medium text-gray-700">Transfer Receipt Image (Optional)</label>
                                <div class="flex items-center space-x-2">
                                    <input type="file" id="transfer_receipt_image_{{ $index }}" wire:model="receipts.{{ $index }}.transfer_receipt_image" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md py-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
                                    <div wire:loading wire:target="receipts.{{ $index }}.transfer_receipt_image">
                                        <span class="text-xs text-blue-500">Uploading...</span>
                                    </div>
                                </div>
                                @error("receipts.$index.transfer_receipt_image") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="sm:col-span-2">
                            <label for="narration_{{ $index }}" class="block text-xs font-medium text-gray-700">Narration</label>
                            <textarea id="narration_{{ $index }}" wire:model="receipts.{{ $index }}.narration" rows="2" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md" required></textarea>
                            @error("receipts.$index.narration") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-end mt-4">
        <button wire:click.prevent="submit" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:opacity-50">
            <span wire:loading.remove>Save Receipts</span>
            <span wire:loading>Saving...</span>
        </button>
    </div>
</div>
