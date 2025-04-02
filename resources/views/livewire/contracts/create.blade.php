<div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Create Contract</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create a new lease contract for your property.</p>
        </div>
        <a href="{{ route('contracts.table') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
            <flux:icon name="arrow-left" class="mr-1.5 -ml-0.5 h-4 w-4" />
            Back to Contracts
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
        <form wire:submit="save" class="p-4 sm:p-5 space-y-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5">
                <div class="col-span-full mb-0">
                    <h3 class="text-base font-medium text-gray-900 dark:text-gray-100 pb-1 border-b border-gray-200 dark:border-gray-700">Contract Details</h3>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contract Number
                    </label>
                    <div class="mt-1">
                        <input type="text" wire:model="name" id="name" readonly class="block w-full rounded-md border-0 py-1.5 pl-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    </div>
                </div>

                <div>
                    <label for="tenant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tenant
                    </label>
                    <div class="mt-1">
                        <select wire:model="tenant_id" id="tenant_id" class="block w-full rounded-md border-0 py-1.5 pl-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            <option value="">Select a tenant</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('tenant_id') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="property_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Property
                    </label>
                    <div class="mt-1">
                        <select wire:model="property_id" id="property_id" class="block w-full rounded-md border-0 py-1.5 pl-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            <option value="">Select a property</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('property_id') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-full mb-0 mt-1">
                    <h3 class="text-base font-medium text-gray-900 dark:text-gray-100 pb-1 border-b border-gray-200 dark:border-gray-700">Financial Details</h3>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Rental Amount
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" wire:model="amount" id="amount" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="0.00">
                    </div>
                    @error('amount') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sec_amt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Security Deposit
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" wire:model="sec_amt" id="sec_amt" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="0.00">
                    </div>
                    @error('sec_amt') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cstart" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Start Date
                    </label>
                    <div class="mt-1">
                        <input type="date" wire:model="cstart" id="cstart" class="block w-full rounded-md border-0 py-1.5 pl-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    </div>
                    @error('cstart') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cend" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        End Date
                    </label>
                    <div class="mt-1">
                        <input type="date" wire:model="cend" id="cend" class="block w-full rounded-md border-0 py-1.5 pl-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    </div>
                    @error('cend') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Ejari Status</legend>
                        <div class="mt-1 space-y-1">
                            <div class="flex items-center">
                                <input id="ejari-yes" wire:model="ejari" type="radio" value="YES" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:focus:ring-offset-gray-800">
                                <label for="ejari-yes" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Yes
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="ejari-no" wire:model="ejari" type="radio" value="NO" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:focus:ring-offset-gray-800">
                                <label for="ejari-no" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    No
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('ejari') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <fieldset>
                        <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Contract Status</legend>
                        <div class="mt-1 space-y-1">
                            <div class="flex items-center">
                                <input id="validity-yes" wire:model="validity" type="radio" value="YES" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:focus:ring-offset-gray-800">
                                <label for="validity-yes" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Active
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input id="validity-no" wire:model="validity" type="radio" value="NO" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:focus:ring-offset-gray-800">
                                <label for="validity-no" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Inactive
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('validity') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-full mb-0 mt-1">
                    <h3 class="text-base font-medium text-gray-900 dark:text-gray-100 pb-1 border-b border-gray-200 dark:border-gray-700">Documentation</h3>
                </div>

                <div class="col-span-full">
                    <label for="cont_copy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contract Documents</label>
                    <div
                        x-data="{
                            isDropping: false,
                            handleDrop(e) {
                                e.preventDefault();
                                isDropping = false;
                                @this.uploadMultiple('cont_copy', e.dataTransfer.files);
                            },
                            handleDragOver(e) {
                                e.preventDefault();
                                isDropping = true;
                            },
                            handleDragLeave(e) {
                                e.preventDefault();
                                isDropping = false;
                            }
                        }"
                        x-on:dragover="handleDragOver($event)"
                        x-on:dragleave="handleDragLeave($event)"
                        x-on:drop="handleDrop($event)"
                        class="mt-1 flex justify-center px-6 pt-4 pb-4 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md"
                        :class="{ 'border-primary-500 bg-primary-50 dark:bg-primary-900/10': isDropping }">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="cont_copy" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500 dark:focus-within:ring-offset-gray-800">
                                    <span>Upload files</span>
                                    <input id="cont_copy" wire:model="cont_copy" type="file" class="sr-only" multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PDF, JPG, JPEG, or PNG (max 10MB)</p>
                        </div>
                    </div>
                    @error('cont_copy.*') <span class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</span> @enderror

                    <div wire:loading wire:target="cont_copy" class="mt-2">
                        <div class="animate-pulse flex space-x-4">
                            <div class="flex-1 space-y-3 py-1">
                                <div class="h-2 bg-gray-300 rounded"></div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="h-2 bg-gray-300 rounded col-span-2"></div>
                                    <div class="h-2 bg-gray-300 rounded col-span-1"></div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Uploading files...</p>
                    </div>

                    @if(!empty($cont_copy) && count($cont_copy) > 0)
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected files:</p>
                            <ul class="mt-1 text-sm text-gray-500 dark:text-gray-400 list-disc pl-5">
                                @foreach($cont_copy as $file)
                                    <li>{{ $file->getClientOriginalName() }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end">
                    <a href="{{ route('contracts.table') }}" class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-red bg-primary-600 hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-600">
                        Create Contract
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
