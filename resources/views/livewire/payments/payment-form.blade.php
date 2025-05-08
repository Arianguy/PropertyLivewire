@php
    $paymentMethods = [
        'cash' => 'Cash',
        'cheque' => 'Cheque',
        'bank_transfer' => 'Bank Transfer',
        'credit_card' => 'Credit Card',
    ];
@endphp

<div class="mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">{{ $editing ? 'Edit Payment' : 'Create Payment' }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $editing ? 'Update payment details.' : 'Record a new payment for your property.' }}
            </p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                <svg class="-ml-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Payments
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
        <form wire:submit="save" class="space-y-6 p-6 sm:p-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-3">
                <!-- Property Selection -->
                <div class="sm:col-span-1">
                    <label for="property_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Property</label>
                    <div class="mt-2">
                        <select id="property_id" wire:model.live="property_id" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:focus:ring-indigo-500">
                            <option value="">Select Property</option>
                            @foreach($this->properties as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Contract Selection -->
                <div class="sm:col-span-1">
                    <label for="contract_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Contract (Optional)</label>
                    <div class="mt-2">
                        <select id="contract_id" wire:model="contract_id" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:focus:ring-indigo-500">
                            <option value="">Select Contract</option>
                            @foreach($this->contracts as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('contract_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Payment Type -->
                <div class="sm:col-span-1">
                    <label for="payment_type_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Payment Type</label>
                    <div class="mt-2">
                        <select id="payment_type_id" wire:model="payment_type_id" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:focus:ring-indigo-500">
                            <option value="">Select Payment Type</option>
                            @foreach($this->paymentTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('payment_type_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Amount -->
                <div class="sm:col-span-1">
                    <label for="amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Amount</label>
                    <div class="mt-2 relative rounded-md shadow-sm">
                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm"></span>
                        </div>
                        <input type="number" step="0.01" id="amount" wire:model="amount" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500" placeholder="0.00">
                        @error('amount') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Payment Date -->
                <div class="sm:col-span-1">
                    <label for="paid_at" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Payment Date</label>
                    <div class="mt-2">
                        <input type="date" id="paid_at" wire:model="paid_at" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500 dark:[color-scheme:dark]">
                        @error('paid_at') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="sm:col-span-1">
                    <label for="payment_method" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Payment Method</label>
                    <div class="mt-2">
                        <select id="payment_method" wire:model="payment_method" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:focus:ring-indigo-500">
                            @foreach($paymentMethods as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_method') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Reference Number -->
                <div class="sm:col-span-1">
                    <label for="reference_number" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Reference Number</label>
                    <div class="mt-2">
                        <input type="text" id="reference_number" wire:model="reference_number" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                        @error('reference_number') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Description</label>
                    <div class="mt-2">
                        <textarea id="description" wire:model="description" rows="2" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500"></textarea>
                        @error('description') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- New File Attachments Section -->
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Add Attachments</label>
                    <div
                        x-data="{
                            isDropping: false,
                            files: @entangle('attachments').live,
                            handleFileDrop(e) {
                                if (e.dataTransfer.files.length > 0) {
                                    const fileList = Array.from(e.dataTransfer.files);
                                    @this.uploadMultiple('attachments', fileList,
                                        (uploadedFilename) => { console.log(uploadedFilename + ' uploaded successfully'); },
                                        () => { console.log('Error uploading files'); },
                                        (event) => { console.log('Upload progress: ' + event.detail.progress + '%'); }
                                    );
                                }
                            }
                        }"
                        class="mt-2"
                    >
                        <div
                            x-on:dragover.prevent="isDropping = true"
                            x-on:dragleave.prevent="isDropping = false"
                            x-on:drop.prevent="isDropping = false; handleFileDrop($event)"
                            class="flex justify-center rounded-lg border border-dashed border-gray-900/25 dark:border-gray-100/25 px-6 py-6"
                            :class="{ 'bg-indigo-50 dark:bg-indigo-900/10 border-indigo-600 dark:border-indigo-400': isDropping }"
                            onclick="document.getElementById('attachments-input').click()"
                        >
                            <div class="text-center">
                                <flux:icon name="cloud-arrow-up" class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                                <div class="mt-3 flex text-sm leading-6 text-gray-600 dark:text-gray-400">
                                    <label for="attachments-input" class="relative cursor-pointer rounded-md bg-white dark:bg-gray-800 font-semibold text-indigo-600 dark:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 dark:focus-within:ring-offset-gray-800 hover:text-indigo-500 dark:hover:text-indigo-300">
                                        <span>Upload files</span>
                                        <input id="attachments-input" wire:model="attachments" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs leading-5 text-gray-600 dark:text-gray-400">PDF, JPG, PNG up to 10MB each (max 5 files)</p>
                            </div>
                        </div>
                        @error('attachments.*') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        @error('attachments') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                        <!-- Upload Progress -->
                         <div wire:loading wire:target="attachments" class="mt-3 w-full" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Uploading files...</div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%" x-bind:style="`width: ${$wire.uploadProgress || 0}%`" x-text="`${$wire.uploadProgress || 0}%`"></div>
                            </div>
                        </div>

                        <!-- Temporary Uploads Preview -->
                        @if (is_array($attachments) && count($attachments) > 0 && method_exists(Arr::first($attachments), 'temporaryUrl'))
                        <div class="mt-4 space-y-2">
                            <h4 class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">New Attachments:</h4>
                             @foreach ($attachments as $index => $attachment)
                                @if ($attachment && method_exists($attachment, 'temporaryUrl'))
                                <div wire:key="temp-attachment-{{ $index }}" class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center space-x-3 truncate">
                                        <flux:icon name="document-text" class="h-5 w-5 text-gray-500 dark:text-gray-400 flex-shrink-0" />
                                        <span class="text-sm text-gray-800 dark:text-gray-200 truncate" title="{{ $attachment->getClientOriginalName() }}">
                                            {{ Str::limit($attachment->getClientOriginalName(), 20) }} ({{ round($attachment->getSize() / 1024, 2) }} KB)
                                        </span>
                                    </div>
                                    <button type="button" wire:click="removeNewAttachment({{ $index }})" class="ml-3 text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 p-1 rounded-md hover:bg-red-100 dark:hover:bg-red-700/50 transition-colors">
                                        <flux:icon name="x-mark" class="h-4 w-4" />
                                        <span class="sr-only">Remove {{ $attachment->getClientOriginalName() }}</span>
                                    </button>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                <!-- End New File Attachments Section -->


                <!-- Existing Attachments Section -->
                @if($editing && !empty($existingAttachments))
                <div class="sm:col-span-2"> <!-- Occupies the first two columns of a new row -->
                    <div class="mt-2 space-y-3"> <!-- Adjusted mt-6 to mt-2 for better alignment with grid row -->
                        <h4 class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Existing Attachments</h4>
                        @foreach($existingAttachments as $attachment)
                            <div wire:key="existing-attachment-{{ $attachment['id'] }}" class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center space-x-3 truncate">
                                    <flux:icon name="document-text" class="h-5 w-5 text-gray-500 dark:text-gray-400 flex-shrink-0" />
                                    <a href="{{ $attachment['url'] ?? '#' }}" target="_blank" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:underline truncate" title="{{ $attachment['file_name'] }}">
                                        {{ Str::limit($attachment['file_name'], 35) }}
                                    </a>
                                </div>
                                <button type="button" wire:click="markAttachmentForRemoval({{ $attachment['id'] }})" class="ml-3 text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 p-1 rounded-md hover:bg-red-100 dark:hover:bg-red-700/50 transition-colors">
                                    Remove
                                    <span class="sr-only">Remove {{ $attachment['file_name'] }}</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <!-- End Existing Attachments Section -->
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700/50 flex items-center justify-end gap-x-3">
                <a href="{{ route('payments.index') }}" class="rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        {{ $editing ? 'Update Payment' : 'Create Payment' }}
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ $editing ? 'Updating...' : 'Creating...' }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
