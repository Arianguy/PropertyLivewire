<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        @csrf

                        @foreach($receipts as $index => $receipt)
                            <div class="border p-6 mb-6 rounded-lg" wire:key="receipt-{{ $index }}">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-lg font-semibold">Receipt {{ $index + 1 }}</h3>
                                    @if(count($receipts) > 1)
                                        <button type="button" wire:click="removeReceipt({{ $index }})" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" wire:loading.remove wire:target="receipts.{{ $index }}.payment_type">
                                    <!-- Payment Type -->
                                    <div class="space-y-2">
                                        <label for="payment_type.{{ $index }}" class="block text-sm font-medium text-gray-700">Payment Type</label>
                                        <select wire:model.live="receipts.{{ $index }}.payment_type" id="payment_type.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="CASH">Cash</option>
                                            <option value="CHEQUE">Cheque</option>
                                            <option value="ONLINE_TRANSFER">Online Transfer</option>
                                        </select>
                                        @error("receipts.$index.payment_type") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Receipt Category -->
                                    <div class="space-y-2">
                                        <label for="receipt_category.{{ $index }}" class="block text-sm font-medium text-gray-700">Category</label>
                                        <select wire:model.live="receipts.{{ $index }}.receipt_category" id="receipt_category.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="RENT">Rent</option>
                                            <option value="SECURITY_DEPOSIT">Security Deposit</option>
                                        </select>
                                        @error("receipts.$index.receipt_category") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Amount -->
                                    <div class="space-y-2">
                                        <label for="amount.{{ $index }}" class="block text-sm font-medium text-gray-700">
                                            @if($receipt['payment_type'] === 'CHEQUE')
                                                Cheque Amount
                                            @elseif($receipt['payment_type'] === 'ONLINE_TRANSFER')
                                                Transfer Amount
                                            @else
                                                Cash Amount
                                            @endif
                                        </label>
                                        <input type="number" step="0.01" wire:model="receipts.{{ $index }}.amount" id="amount.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        @error("receipts.$index.amount") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Date -->
                                    <div class="space-y-2">
                                        <label for="receipt_date.{{ $index }}" class="block text-sm font-medium text-gray-700">
                                            @if($receipt['payment_type'] === 'CHEQUE')
                                                Cheque Date
                                            @elseif($receipt['payment_type'] === 'ONLINE_TRANSFER')
                                                Transfer Receipt Date
                                            @else
                                                Cash Receipt Date
                                            @endif
                                        </label>
                                        <input type="date" wire:model="receipts.{{ $index }}.receipt_date" id="receipt_date.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        @error("receipts.$index.receipt_date") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    @if($receipt['payment_type'] === 'CHEQUE')
                                        <!-- Cheque Number -->
                                        <div class="space-y-2">
                                            <label for="cheque_no.{{ $index }}" class="block text-sm font-medium text-gray-700">Cheque Number</label>
                                            <input type="text" wire:model="receipts.{{ $index }}.cheque_no" id="cheque_no.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            @error("receipts.$index.cheque_no") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Bank -->
                                        <div class="space-y-2">
                                            <label for="cheque_bank.{{ $index }}" class="block text-sm font-medium text-gray-700">Bank</label>
                                            <select wire:model="receipts.{{ $index }}.cheque_bank" id="cheque_bank.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                                <option value="">Select Bank</option>
                                                @foreach($banks as $code => $name)
                                                    <option value="{{ $code }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error("receipts.$index.cheque_bank") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Cheque Image -->
                                        <div class="space-y-2 col-span-2">
                                            <label for="cheque_image.{{ $index }}" class="block text-sm font-medium text-gray-700">Attach Cheque Copy</label>
                                            <input type="file" wire:model="receipts.{{ $index }}.cheque_image" id="cheque_image.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            @error("receipts.$index.cheque_image") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                            @if ($receipt['cheque_image'])
                                                <div class="mt-2">
                                                    <img src="{{ $receipt['cheque_image']->temporaryUrl() }}" alt="Cheque Image" class="h-20">
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if($receipt['payment_type'] === 'ONLINE_TRANSFER')
                                        <!-- Transaction Reference -->
                                        <div class="space-y-2">
                                            <label for="transaction_reference.{{ $index }}" class="block text-sm font-medium text-gray-700">Transaction Reference</label>
                                            <input type="text" wire:model="receipts.{{ $index }}.transaction_reference" id="transaction_reference.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            @error("receipts.$index.transaction_reference") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Transfer Receipt Image -->
                                        <div class="space-y-2 col-span-2">
                                            <label for="transfer_receipt_image.{{ $index }}" class="block text-sm font-medium text-gray-700">Attach Transfer Receipt</label>
                                            <input type="file" wire:model="receipts.{{ $index }}.transfer_receipt_image" id="transfer_receipt_image.{{ $index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                            @error("receipts.$index.transfer_receipt_image") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                            @if ($receipt['transfer_receipt_image'])
                                                <div class="mt-2">
                                                    <img src="{{ $receipt['transfer_receipt_image']->temporaryUrl() }}" alt="Transfer Receipt Image" class="h-20">
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Narration -->
                                    <div class="space-y-2 col-span-2">
                                        <label for="narration.{{ $index }}" class="block text-sm font-medium text-gray-700">Narration</label>
                                        <textarea wire:model="receipts.{{ $index }}.narration" id="narration.{{ $index }}" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                                        @error("receipts.$index.narration") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Loading State -->
                                <div wire:loading wire:target="receipts.{{ $index }}.payment_type" class="text-center py-4">
                                    <svg class="animate-spin h-5 w-5 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                        @endforeach

                        <div class="flex justify-between items-center mt-6">
                            <button type="button" wire:click="addReceipt" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                + Add Another Receipt
                            </button>

                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Record Receipts
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
