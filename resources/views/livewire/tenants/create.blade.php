<div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Add New Tenant</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create a new tenant record</p>
        </div>
        <a href="{{ route('tenants.table') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
            Back to List
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
        <form wire:submit="save" class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Basic Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Basic Information</h3>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Name</label>
                    <div class="mt-2">
                        <input type="text" wire:model="name" id="name" class="block w-full rounded-md border-0 py-2 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                        @error('name') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Email</label>
                    <div class="mt-2">
                        <input type="email" wire:model="email" id="email" class="block w-full rounded-md border-0 py-2 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                        @error('email') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="mobile" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Mobile</label>
                    <div class="mt-2">
                        <input type="text" wire:model="mobile" id="mobile" class="block w-full rounded-md border-0 py-2 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                        @error('mobile') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="nationality" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Nationality</label>
                    <div class="mt-2">
                        <input type="text" wire:model="nationality" id="nationality" class="block w-full rounded-md border-0 py-2 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                        @error('nationality') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Passport Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Passport Information</h3>
                </div>

                <div>
                    <label for="passport_no" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Passport Number</label>
                    <div class="mt-2">
                        <input type="text" wire:model="passport_no" id="passport_no" class="block w-full rounded-md border-0 py-2 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                        @error('passport_no') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="passport_expiry" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Passport Expiry Date</label>
                    <div class="mt-2">
                        <input type="date" wire:model="passport_expiry" id="passport_expiry" class="block w-full rounded-md border-0 py-2 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                        @error('passport_expiry') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Visa Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Visa Information</h3>
                </div>

                <div>
                    <label for="visa_expiry" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Visa Expiry Date</label>
                    <div class="mt-2">
                        <input type="date" wire:model="visa_expiry" id="visa_expiry" class="block w-full rounded-md border-0 py-2 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                        @error('visa_expiry') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Document Upload</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Documents</label>
                    <div
                        x-data="{
                            isDropping: false,
                            handleDrop(e) {
                                e.preventDefault();
                                isDropping = false;
                                @this.uploadMultiple('passport_files', e.dataTransfer.files);
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
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md"
                        :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': isDropping }">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="passport_files" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 dark:focus-within:ring-offset-gray-800">
                                    <span>Upload files</span>
                                    <input id="passport_files" wire:model="passport_files" type="file" class="sr-only" multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB</p>
                        </div>
                    </div>
                    @error('passport_files') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror

                    @if(!empty($passport_files) && count($passport_files) > 0)
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected files:</p>
                            <ul class="mt-1 text-sm text-gray-500 dark:text-gray-400 list-disc pl-5">
                                @foreach($passport_files as $file)
                                    <li>{{ $file->getClientOriginalName() }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visa Documents</label>
                    <div
                        x-data="{
                            isDropping: false,
                            handleDrop(e) {
                                e.preventDefault();
                                isDropping = false;
                                @this.uploadMultiple('visa_files', e.dataTransfer.files);
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
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md"
                        :class="{ 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10': isDropping }">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="visa_files" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 dark:focus-within:ring-offset-gray-800">
                                    <span>Upload files</span>
                                    <input id="visa_files" wire:model="visa_files" type="file" class="sr-only" multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB</p>
                        </div>
                    </div>
                    @error('visa_files') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror

                    @if(!empty($visa_files) && count($visa_files) > 0)
                        <div class="mt-2">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected files:</p>
                            <ul class="mt-1 text-sm text-gray-500 dark:text-gray-400 list-disc pl-5">
                                @foreach($visa_files as $file)
                                    <li>{{ $file->getClientOriginalName() }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Tenant
                </button>
            </div>
        </form>
    </div>
</div>
