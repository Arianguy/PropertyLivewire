<div class="p-4">
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
                            <select id="payment_type_{{ $index }}" wire:model.live="receipts.{{ $index }}.payment_type" class="mt-1 block w-full pl-3 pr-10 py-1.5 text-sm border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                                <option value="CASH">Cash</option>
                                <option value="CHEQUE">Cheque</option>
                                <option value="ONLINE_TRANSFER">Online Transfer</option>
                            </select>
                            @error("receipts.$index.payment_type") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="receipt_category_{{ $index }}" class="block text-xs font-medium text-gray-700">Category</label>
                            <select id="receipt_category_{{ $index }}" wire:model.live="receipts.{{ $index }}.receipt_category" class="mt-1 block w-full pl-3 pr-10 py-1.5 text-sm border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md">
                                <option value="RENT">Rent</option>
                                <option value="SECURITY_DEPOSIT">Security Deposit</option>
                            </select>
                            @error("receipts.$index.receipt_category") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

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

                        <div>
                            <label for="receipt_date_{{ $index }}" class="block text-xs font-medium text-gray-700">
                                @if($receipt['payment_type'] === 'CASH')
                                    Cash Receipt Date
                                @elseif($receipt['payment_type'] === 'CHEQUE')
                                    Cheque Date
                                @else
                                    Transfer Receipt Date
                                @endif
                            </label>
                            <input type="date" id="receipt_date_{{ $index }}" wire:model="receipts.{{ $index }}.receipt_date" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                            @error("receipts.$index.receipt_date") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        @if($receipt['payment_type'] === 'CHEQUE')
                            <div>
                                <label for="cheque_no_{{ $index }}" class="block text-xs font-medium text-gray-700">Cheque No</label>
                                <input type="text" id="cheque_no_{{ $index }}" wire:model="receipts.{{ $index }}.cheque_no" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                                @error("receipts.$index.cheque_no") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="cheque_bank_{{ $index }}" class="block text-xs font-medium text-gray-700">Cheque Bank</label>
                                <select id="cheque_bank_{{ $index }}" wire:model="receipts.{{ $index }}.cheque_bank" class="mt-1 block w-full pl-3 pr-10 py-1.5 text-sm border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md" required>
                                    <option value="">Select Bank</option>
                                    <option value="ENBD">Emirates NBD</option>
                                    <option value="CBD">Commercial Bank of Dubai</option>
                                    <option value="FAB">First Abu Dhabi Bank</option>
                                    <option value="Mashreq Bank">Mashreq Bank</option>
                                    <option value="DIB">Dubai Islamic Bank</option>
                                    <option value="EIB">Emirates Islamic Bank</option>
                                </select>
                                @error("receipts.$index.cheque_bank") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="cheque_image_{{ $index }}" class="block text-xs font-medium text-gray-700">Cheque Copy</label>
                                <input type="file" id="cheque_image_{{ $index }}" wire:model="receipts.{{ $index }}.cheque_image" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full text-sm border-gray-300 rounded-md py-1.5" accept="image/*" required>
                                @error("receipts.$index.cheque_image") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($receipt['payment_type'] === 'ONLINE_TRANSFER')
                            <div class="sm:col-span-2">
                                <label for="transaction_reference_{{ $index }}" class="block text-xs font-medium text-gray-700">Transaction Reference</label>
                                <input type="text" id="transaction_reference_{{ $index }}" wire:model="receipts.{{ $index }}.transaction_reference" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm text-sm border-gray-300 rounded-md py-1.5" required>
                                @error("receipts.$index.transaction_reference") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
        <button type="button" wire:click="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500">
            Save Receipts
        </button>
    </div>
</div>
