<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Payment</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Update the details for this payment.
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                Back to Payments
            </a>
        </div>
    </div>

    <form wire:submit.prevent="update" class="mt-6 space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
         <div class="space-y-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">

                {{-- Property --}}
                <div>
                    <label for="property_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Property*</label>
                    <div class="mt-2">
                        <select wire:model.live="property_id" id="property_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('property_id') ring-red-500 @enderror">
                            <option value="">Select Property</option>
                            @foreach($properties as $id => $name)
                                <option value="{{ $id }}" {{ $property_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Contract (Optional) --}}
                <div>
                    <label for="contract_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Contract (Optional)</label>
                    <div class="mt-2">
                         <select wire:model="contract_id" id="contract_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('contract_id') ring-red-500 @enderror" @if($contracts->isEmpty()) disabled @endif>
                            <option value="">Select Contract (if applicable)</option>
                            @foreach($contracts as $id => $name)
                                <option value="{{ $id }}" {{ $contract_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                         @if($contracts->isEmpty() && !empty($property_id))
                            <span class="text-gray-500 text-xs mt-1">No relevant contracts found for selected property.</span>
                        @endif
                    </div>
                    @error('contract_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Payment Type --}}
                 <div>
                    <label for="payment_type_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Payment Type*</label>
                    <div class="mt-2">
                        <select wire:model="payment_type_id" id="payment_type_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('payment_type_id') ring-red-500 @enderror">
                            <option value="">Select Payment Type</option>
                            @foreach($paymentTypes as $id => $name)
                                <option value="{{ $id }}" {{ $payment_type_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('payment_type_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Amount --}}
                <div>
                    <label for="amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Amount*</label>
                     <div class="mt-2 relative rounded-md shadow-sm">
                         <input type="number" step="0.01" wire:model="amount" id="amount" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('amount') ring-red-500 @enderror" placeholder="0.00">
                     </div>
                    @error('amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Paid At Date --}}
                <div>
                    <label for="paid_at" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Payment Date*</label>
                    <div class="mt-2">
                        <input type="date" wire:model="paid_at" id="paid_at" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('paid_at') ring-red-500 @enderror">
                    </div>
                     @error('paid_at') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Description</label>
                    <div class="mt-2">
                         <textarea wire:model="description" id="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('description') ring-red-500 @enderror"></textarea>
                    </div>
                    @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Attachment Upload --}}
                 <div class="sm:col-span-2"
                      x-data="{
                          dropping: false,
                          handleDrop(event) {
                              @this.uploadMultiple('new_attachments', event.dataTransfer.files);
                          }
                      }"
                      x-on:dragover.prevent="dropping = true"
                      x-on:dragleave.prevent="dropping = false"
                      x-on:drop.prevent="dropping = false; handleDrop($event)"
                 >
                    <label for="new_attachments" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Attachments</label>

                    {{-- Display Existing Attachments --}}
                    @if($existing_attachments && $existing_attachments->count() > 0)
                        <div class="mt-2 space-y-2">
                            <h4 class="text-xs font-semibold text-gray-600 dark:text-gray-400">Existing Files:</h4>
                            @foreach ($existing_attachments as $attachment)
                                <div class="flex items-center space-x-3 p-2 rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                    <flux:icon name="paper-clip" class="h-5 w-5 text-gray-400 flex-shrink-0" />
                                    <a href="{{ $attachment->getUrl() }}" target="_blank" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline flex-grow truncate" title="{{ $attachment->file_name }}">
                                        {{ $attachment->file_name }}
                                    </a>
                                    <span class="text-xs text-gray-500 flex-shrink-0">({{ $attachment->human_readable_size }})</span>
                                    <button type="button" wire:click="removeAttachment({{ $attachment->id }})" wire:confirm="Are you sure you want to remove this attachment?" class="text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400 ml-auto flex-shrink-0">
                                        <flux:icon name="x-circle" class="w-5 h-5"/>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-4">
                        <label for="new_attachments"
                               :class="{ 'border-primary-600 bg-primary-50 dark:bg-primary-900/30': dropping, 'border-gray-300 dark:border-gray-600': !dropping }"
                               class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:hover:border-gray-500 dark:hover:bg-gray-600 transition-colors duration-150 ease-in-out">

                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                <flux:icon name="arrow-up-tray" class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" />
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-300">PDF, DOC, DOCX, JPG, PNG (MAX. 10MB each).</p>
                            </div>
                            <input id="new_attachments" type="file" wire:model="new_attachments" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" aria-describedby="attachment_help" />
                        </label>

                        <div wire:loading wire:target="new_attachments" class="text-sm text-primary-600 mt-1">Uploading...</div>
                    </div>
                    @error('new_attachments.*') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    {{-- Error for the array itself (e.g., too many files) --}}
                    @error('new_attachments') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                    {{-- Preview for new uploads --}}
                    @if ($new_attachments)
                        <div class="mt-4 space-y-2">
                             <h4 class="text-xs font-semibold text-gray-600 dark:text-gray-400">Files to Upload:</h4>
                             @foreach ($new_attachments as $index => $attachment)
                                <div class="flex items-center space-x-2 p-2 rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 text-sm">
                                    @if (method_exists($attachment, 'temporaryUrl') && str_starts_with($attachment->getMimeType(), 'image/'))
                                        <img src="{{ $attachment->temporaryUrl() }}" class="h-10 w-10 rounded object-cover flex-shrink-0">
                                    @else
                                        <flux:icon name="document" class="h-8 w-8 text-gray-400 flex-shrink-0" />
                                    @endif
                                    <span class="flex-grow truncate" title="{{ $attachment->getClientOriginalName() }}">{{ $attachment->getClientOriginalName() }}</span>
                                    <span class="text-xs text-gray-500 flex-shrink-0">({{ ound($attachment->getSize() / 1024 / 1024, 2) }} MB)</span>
                                     {{-- Add a remove button for temporary uploads if needed --}}
                                     {{-- <button type="button" wire:click="removeNewAttachment({{ $index }})" class="text-red-500"><flux:icon name="x-circle" class="w-4 h-4"/></button> --}}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="pt-5">
            <div class="flex justify-end gap-x-3">
                 <a href="{{ route('payments.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="inline-flex justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                    <span wire:loading wire:target="update">Updating...</span>
                    <span wire:loading.remove wire:target="update">Update Payment</span>
                </button>
            </div>
        </div>
    </form>
</div>
