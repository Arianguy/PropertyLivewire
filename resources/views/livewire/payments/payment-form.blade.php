@php
    $paymentMethods = [
        'cash' => 'Cash',
        'cheque' => 'Cheque',
        'bank_transfer' => 'Bank Transfer',
        'credit_card' => 'Credit Card',
    ];
@endphp

<div class="mx-auto max-w-7xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $editing ? 'Edit Payment' : 'Create Payment' }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $editing ? 'Update payment details.' : 'Record a new payment for your property.' }}
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="-ml-0.5 h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Payments
            </a>
        </div>
    </div>

    <form wire:submit="save" class="mt-6 space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                <!-- Property Selection -->
                <div>
                    <label for="property_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Property</label>
                    <div class="mt-2">
                        <select id="property_id" wire:model.live="property_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            <option value="">Select Property</option>
                            @foreach($this->properties as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Contract Selection -->
                <div>
                    <label for="contract_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Contract (Optional)</label>
                    <div class="mt-2">
                        <select id="contract_id" wire:model="contract_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            <option value="">Select Contract</option>
                            @foreach($this->contracts as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('contract_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Payment Type -->
                <div>
                    <label for="payment_type_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Payment Type</label>
                    <div class="mt-2">
                        <select id="payment_type_id" wire:model="payment_type_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            <option value="">Select Payment Type</option>
                            @foreach($this->paymentTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('payment_type_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Amount</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm"></span>
                        </div>
                        <input type="number" step="0.01" id="amount" wire:model="amount" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="0.00">
                        @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Payment Date -->
                <div>
                    <label for="paid_at" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Payment Date</label>
                    <div class="mt-2">
                        <input type="date" id="paid_at" wire:model="paid_at" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                        @error('paid_at') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Payment Method</label>
                    <div class="mt-2">
                        <select id="payment_method" wire:model="payment_method" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            @foreach($paymentMethods as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Reference Number -->
                <div>
                    <label for="reference_number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Reference Number</label>
                    <div class="mt-2">
                        <input type="text" id="reference_number" wire:model="reference_number" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                        @error('reference_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="col-span-full">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Description</label>
                    <div class="mt-2">
                        <textarea id="description" wire:model="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- File Attachments -->
                <div class="col-span-full">
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Attachments</label>
                    <div
                        x-data="{
                            isDropping: false,
                            files: [],
                            handleFileDrop(e) {
                                if (e.dataTransfer.files.length > 0) {
                                    const fileList = Array.from(e.dataTransfer.files);
                                    @this.uploadMultiple('attachments', fileList);
                                    this.files = [...this.files, ...fileList.map(f => f.name)];
                                }
                            },
                            handleFileSelect(e) {
                                if (e.target.files.length > 0) {
                                    const fileList = Array.from(e.target.files);
                                    this.files = [...this.files, ...fileList.map(f => f.name)];
                                }
                            }
                        }"
                        x-on:dragover.prevent="isDropping = true"
                        x-on:dragleave.prevent="isDropping = false"
                        x-on:drop.prevent="isDropping = false; handleFileDrop($event)"
                        class="mt-2"
                    >
                        <div class="flex items-center justify-center w-full">
                            <label for="attachments" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500"
                                :class="{ 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/10': isDropping }">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <div class="flex flex-col items-center" x-show="files.length === 0">
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PDF or image files up to 10MB</p>
                                    </div>
                                    <div x-show="files.length > 0" class="flex flex-col items-center">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Selected files: <span class="font-semibold" x-text="files.length"></span>
                                        </p>
                                        <ul class="mt-1 text-xs text-gray-500 dark:text-gray-400 max-h-20 overflow-y-auto">
                                            <template x-for="file in files" :key="file">
                                                <li x-text="file"></li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                                <input
                                    id="attachments"
                                    wire:model="attachments"
                                    type="file"
                                    class="hidden"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    multiple
                                    x-on:change="handleFileSelect($event)"
                                >
                            </label>
                        </div>
                    </div>
                    @error('attachments.*') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror

                    @if($editing && !empty($existingAttachments))
                        <div class="mt-4 space-y-2">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200">Existing Attachments</h4>
                            @foreach($existingAttachments as $attachment)
                                <div wire:key="existing-attachment-{{ $attachment['id'] }}" class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center space-x-2 truncate">
                                        <flux:icon name="document-text" class="h-5 w-5 text-gray-400 flex-shrink-0" />
                                        <a href="{{ $attachment['url'] ?? '#' }}" target="_blank" class="text-sm text-gray-700 dark:text-gray-200 hover:underline truncate" title="{{ $attachment['file_name'] }}">
                                            {{ $attachment['file_name'] }}
                                        </a>
                                    </div>
                                    <button type="button" wire:click="markAttachmentForRemoval({{ $attachment['id'] }})" class="ml-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium">
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="{{ route('payments.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">Cancel</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <span wire:loading.remove wire:target="save">
                    {{ $editing ? 'Update Payment' : 'Create Payment' }}
                </span>
                <span wire:loading wire:target="save">
                    {{ $editing ? 'Updating...' : 'Creating...' }}
                </span>
            </button>
        </div>
    </form>
</div>
