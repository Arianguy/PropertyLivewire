<div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Edit Tenant</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update tenant information</p>
        </div>
        <a href="{{ route('tenants.table') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
            Back to List
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
        <form wire:submit="save" class="p-4 sm:p-6 lg:p-8">
            <!-- Debug Info (Remove this in production) -->
            @if(session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Basic Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Basic Information</h3>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                    @error('name') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" wire:model="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                    @error('email') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile</label>
                    <input type="text" wire:model="mobile" id="mobile" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                    @error('mobile') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nationality</label>
                    <input type="text" wire:model="nationality" id="nationality" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                    @error('nationality') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Passport Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Passport Information</h3>
                </div>

                <div>
                    <label for="passport_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Number</label>
                    <input type="text" wire:model="passport_no" id="passport_no" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                    @error('passport_no') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="passport_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Expiry Date</label>
                    <input type="date" wire:model="passport_expiry" id="passport_expiry" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                    @error('passport_expiry') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Visa Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Visa Information</h3>
                </div>

                <div>
                    <label for="visa_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visa Expiry Date</label>
                    <input type="date" wire:model="visa_expiry" id="visa_expiry" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-100">
                    @error('visa_expiry') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Document Management -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Document Management</h3>
                </div>

                <!-- Passport Documents -->
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
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">New files to upload:</p>
                            <ul class="mt-1 text-sm text-gray-500 dark:text-gray-400 list-disc pl-5">
                                @foreach($passport_files as $file)
                                    <li>{{ $file->getClientOriginalName() }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(count($passportMedia) > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Documents:</h4>
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach($passportMedia as $media)
                                    <div class="relative group">
                                        @if(str_contains($media['mime_type'], 'image'))
                                            <img src="{{ $media['url'] }}" alt="Passport document" class="h-24 w-24 object-cover rounded-lg">
                                        @else
                                            <div class="h-24 w-24 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg">
                                                <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                            <div class="flex space-x-2">
                                                <a href="{{ $media['url'] }}" target="_blank" class="p-1 bg-white rounded-full text-gray-800 hover:text-indigo-600">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <button type="button" wire:click="deleteMedia({{ $media['id'] }})" wire:confirm="Are you sure you want to delete this document?" class="p-1 bg-white rounded-full text-gray-800 hover:text-red-600">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Visa Documents -->
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
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">New files to upload:</p>
                            <ul class="mt-1 text-sm text-gray-500 dark:text-gray-400 list-disc pl-5">
                                @foreach($visa_files as $file)
                                    <li>{{ $file->getClientOriginalName() }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(count($visaMedia) > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Documents:</h4>
                            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach($visaMedia as $media)
                                    <div class="relative group">
                                        @if(str_contains($media['mime_type'], 'image'))
                                            <img src="{{ $media['url'] }}" alt="Visa document" class="h-24 w-24 object-cover rounded-lg">
                                        @else
                                            <div class="h-24 w-24 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg">
                                                <svg class="h-8 w-8 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                            <div class="flex space-x-2">
                                                <a href="{{ $media['url'] }}" target="_blank" class="p-1 bg-white rounded-full text-gray-800 hover:text-indigo-600">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <button type="button" wire:click="deleteMedia({{ $media['id'] }})" wire:confirm="Are you sure you want to delete this document?" class="p-1 bg-white rounded-full text-gray-800 hover:text-red-600">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Tenant
                </button>
            </div>
        </form>
    </div>
</div>
