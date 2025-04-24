<div x-data="{ paymentType: @entangle('payment_type') }">
    <div class="py-12 bg-gradient-to-b from-gray-100 dark:from-gray-800 to-blue-50 dark:to-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Edit Receipt #{{ $receipt->id }}</h2>
                        <p class="text-gray-600 dark:text-gray-400">Contract: {{ $contract->name }}</p>
                    </div>
                    <div>
                        <a href="{{ route('receipts.list-by-contract', $contract->id) }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Receipts
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contract Summary Section -->
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex items-start gap-2 mb-4">
                    <div class="text-blue-800 dark:text-blue-300 text-sm font-medium uppercase tracking-wide">CONTRACT DETAILS</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Tenant</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $contract->tenant->name }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Property</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $contract->property->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Contract Period</p>
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $contract->cstart->format('d M Y') }} - {{ $contract->cend->format('d M Y') }}</p>
        </div>
                </div>
            </div>

            <!-- Receipt Form -->
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form wire:submit="save" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Payment Type -->
                        <div class="space-y-2">
                            <label for="payment_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Type</label>
                            <select id="payment_type" wire:model.live="payment_type" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="CASH">Cash</option>
                                <option value="CHEQUE">Cheque</option>
                                <option value="ONLINE_TRANSFER">Online Transfer</option>
                            </select>
                            @error('payment_type')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Receipt Category -->
                        <div class="space-y-2">
                            <label for="receipt_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Receipt Category</label>
                            <select id="receipt_category" wire:model="receipt_category" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="RENT">Rent</option>
                                <option value="RETURN CHEQUE">Return Cheque</option>
                                <option value="SECURITY_DEPOSIT">Security Deposit</option>
                            </select>
                            @error('receipt_category')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="space-y-2">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                            <input type="number" step="0.01" id="amount" wire:model="amount" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('amount')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Receipt Date -->
                        <div class="space-y-2">
                            <label for="receipt_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Receipt Date</label>
                            <input type="date" id="receipt_date" wire:model="receipt_date" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:[color-scheme:dark]">
                            @error('receipt_date')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Narration -->
                        <div class="space-y-2 md:col-span-2">
                            <label for="narration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Narration</label>
                            <input type="text" id="narration" wire:model="narration" required
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('narration')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status (only shown for non-CASH payments) -->
                        <div class="space-y-2" x-show="paymentType !== 'CASH'">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select id="status" wire:model="status"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                x-bind:disabled="paymentType === 'CASH'">
                                <option value="PENDING">Pending</option>
                                <option value="CLEARED">Cleared</option>
                                <option value="BOUNCED">Bounced</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status (only for CASH payments) -->
                        <div class="space-y-2" x-show="paymentType === 'CASH'">
                            <label for="cash_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select id="cash_status" disabled
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 dark:text-gray-400 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="CLEARED" selected>Cleared</option>
                            </select>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cash payments are always cleared.</p>
                        </div>
                    </div>

                    <!-- Cheque specific fields -->
                    <div class="space-y-4" x-show="paymentType === 'CHEQUE'">
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Cheque Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label for="cheque_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cheque Number</label>
                                <input type="text" id="cheque_no" wire:model="cheque_no"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('cheque_no')
                                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="cheque_bank" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cheque Bank</label>
                                <select id="cheque_bank" wire:model="cheque_bank"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('cheque_bank')
                                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="cheque_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cheque Date</label>
                                <input type="date" id="cheque_date" wire:model="cheque_date"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:[color-scheme:dark]">
                                @error('cheque_date')
                                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="cheque_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cheque Image</label>
                            @if($receipt->hasChequeImage())
                                <div class="mb-2 flex items-center">
                                    <img src="{{ $receipt->getChequeImageUrl() }}" alt="Cheque Image" class="h-24 object-cover rounded border border-gray-300 dark:border-gray-600">
                                    <button type="button" wire:click="$dispatch('showAttachment', { receipt: {{ $receipt->id }}, type: 'cheque' })"
                                        class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        View Full Image
                                    </button>
                                    <div class="ml-4">
                                        <div class="flex items-center mb-1">
                                            <input id="remove_cheque_image" wire:model="remove_cheque_image" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded">
                                            <label for="remove_cheque_image" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Remove current image</label>
                                        </div>
            </div>
        </div>
    @endif
                            <div class="flex items-center space-x-2">
                                <input type="file" id="cheque_image" wire:model="cheque_image" accept="image/*"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-600 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-gray-500">
                                <div wire:loading wire:target="cheque_image">
                                    <span class="text-xs text-blue-500 dark:text-blue-400">Uploading...</span>
                                </div>
                            </div>
                            @error('cheque_image')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Online Transfer specific fields -->
                    <div class="space-y-4" x-show="paymentType === 'ONLINE_TRANSFER'">
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Transfer Details</h4>
                        <div class="space-y-2">
                            <label for="transaction_reference" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transaction Reference</label>
                            <input type="text" id="transaction_reference" wire:model="transaction_reference"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('transaction_reference')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="transfer_receipt_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Transfer Receipt Image</label>
                            @if($receipt->hasTransferReceiptImage())
                                <div class="mb-2 flex items-center">
                                    <img src="{{ $receipt->getTransferReceiptImageUrl() }}" alt="Transfer Receipt" class="h-24 object-cover rounded border border-gray-300 dark:border-gray-600">
                                    <button type="button" wire:click="$dispatch('showAttachment', { receipt: {{ $receipt->id }}, type: 'transfer' })"
                                        class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        View Full Image
                    </button>
                                    <div class="ml-4">
                                        <div class="flex items-center mb-1">
                                            <input id="remove_transfer_image" wire:model="remove_transfer_image" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 dark:border-gray-600 rounded">
                                            <label for="remove_transfer_image" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Remove current image</label>
                                        </div>
                                    </div>
                </div>
            @endif
                            <div class="flex items-center space-x-2">
                                <input type="file" id="transfer_receipt_image" wire:model="transfer_receipt_image" accept="image/*"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-600 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-gray-500">
                                <div wire:loading wire:target="transfer_receipt_image">
                                    <span class="text-xs text-blue-500 dark:text-blue-400">Uploading...</span>
                                </div>
                            </div>
                            @error('transfer_receipt_image')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between pt-5 border-t border-gray-200 dark:border-gray-600">
                        <button type="button" wire:click="delete" onclick="return confirm('Are you sure you want to delete this receipt?')"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Delete Receipt
                        </button>
                        <div class="flex space-x-3">
                            <a href="{{ route('receipts.list-by-contract', $contract->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-500 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Receipt
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @livewire('receipts.view-attachment-modal')
</div>
