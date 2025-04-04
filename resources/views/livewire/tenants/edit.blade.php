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
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Basic Information</h3>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6" placeholder="Enter tenant name">
                    @error('name') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" wire:model="email" id="email" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6" placeholder="Enter email address">
                    @error('email') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile</label>
                    <input type="text" wire:model="mobile" id="mobile" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6" placeholder="Enter mobile number (10-15 digits)" minlength="10" maxlength="15">
                    @error('mobile') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nationality</label>
                    <select wire:model="nationality" id="nationality" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                        @foreach($nationalityOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('nationality') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- ID Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 mt-2 pb-2 border-b border-gray-200 dark:border-gray-700">Emirates ID Information</h3>
                </div>

                <div>
                    <label for="eid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emirates ID</label>
                    <input type="text" wire:model.live="eid" id="eid" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6" placeholder="Enter Emirates ID (15 digits)">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter all 15 digits without dashes. Will be automatically formatted as xxx-xxxx-xxxxxxx-x</p>
                    @error('eid') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="eidexp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emirates ID Expiry Date</label>
                    <input type="date" wire:model="eidexp" id="eidexp" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                    @error('eidexp') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Passport Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 mt-2 pb-2 border-b border-gray-200 dark:border-gray-700">Passport Information</h3>
                </div>

                <div>
                    <label for="passport_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Number</label>
                    <input type="text" wire:model="passport_no" id="passport_no" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6" placeholder="Enter passport number">
                    @error('passport_no') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="passport_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Expiry Date</label>
                    <input type="date" wire:model="passport_expiry" id="passport_expiry" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Date must be in the future</p>
                    @error('passport_expiry') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Visa Information -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 mt-2 pb-2 border-b border-gray-200 dark:border-gray-700">Visa Information</h3>
                </div>

                <div>
                    <label for="visa" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visa of Company</label>
                    <input type="text" wire:model="visa" id="visa" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6" placeholder="Enter visa company information" minlength="5" maxlength="95">
                    @error('visa') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="visa_expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visa Expiry Date</label>
                    <input type="date" wire:model="visa_expiry" id="visa_expiry" class="mt-1 block w-full rounded-md border-0 py-3 pl-4 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-base sm:leading-6">
                    @error('visa_expiry') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>

                <!-- Document Management -->
                <div class="sm:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 mt-2 pb-2 border-b border-gray-200 dark:border-gray-700">Document Management</h3>
                </div>

                <!-- Passport Documents -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Documents <span class="text-red-600">*</span></label>
                    <div
                        x-data="{
                            isDropping: false,
                            fileCount: 0,
                            handleDrop(e) {
                                e.preventDefault();
                                isDropping = false;
                                @this.uploadMultiple('passport_files', e.dataTransfer.files);
                            },
                            handleFileSelect(e) {
                                @this.uploadMultiple('passport_files', e.target.files);
                            },
                            updateFileCount() {
                                this.fileCount = @this.passport_files ? @this.passport_files.length : 0;
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
                        x-init="$watch('$wire.passport_files', () => { updateFileCount() })"
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md"
                        :class="{ 'border-primary-500 bg-primary-50 dark:bg-primary-900/10': isDropping }">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="passport_files" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500 dark:focus-within:ring-offset-gray-800">
                                    <span>Upload files</span>
                                    <input id="passport_files" wire:model="passport_files" x-on:change="handleFileSelect" type="file" class="sr-only" multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB (Required)</p>
                            <p class="text-xs font-medium text-primary-600 dark:text-primary-400">Multiple files allowed</p>
                            <div x-show="fileCount > 0" class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                    <span x-text="fileCount"></span> <span x-text="fileCount === 1 ? 'file' : 'files'"></span> selected
                                </span>
                            </div>
                        </div>
                    </div>
                    @error('passport_files') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror

                    @if(!empty($passport_files))
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">New Files:</h4>
                        <ul class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($passport_files as $index => $file)
                            <li class="py-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $file->getClientOriginalName() }}</span>
                                </div>
                                <button type="button" wire:click="removeFile('passport_files', {{ $index }})" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(count($passportMedia) > 0)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Files:</h4>
                        <ul class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($passportMedia as $media)
                            <li class="py-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $media['file_name'] }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ $media['url'] }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button type="button" wire:click="deleteMedia({{ $media['id'] }})" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>

                <!-- Visa Documents -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visa Documents <span class="text-red-600">*</span></label>
                    <div
                        x-data="{
                            isDropping: false,
                            fileCount: 0,
                            handleDrop(e) {
                                e.preventDefault();
                                isDropping = false;
                                @this.uploadMultiple('visa_files', e.dataTransfer.files);
                            },
                            handleFileSelect(e) {
                                @this.uploadMultiple('visa_files', e.target.files);
                            },
                            updateFileCount() {
                                this.fileCount = @this.visa_files ? @this.visa_files.length : 0;
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
                        x-init="$watch('$wire.visa_files', () => { updateFileCount() })"
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md"
                        :class="{ 'border-primary-500 bg-primary-50 dark:bg-primary-900/10': isDropping }">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="visa_files" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500 dark:focus-within:ring-offset-gray-800">
                                    <span>Upload files</span>
                                    <input id="visa_files" wire:model="visa_files" x-on:change="handleFileSelect" type="file" class="sr-only" multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB (Required)</p>
                            <p class="text-xs font-medium text-primary-600 dark:text-primary-400">Multiple files allowed</p>
                            <div x-show="fileCount > 0" class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100">
                                    <span x-text="fileCount"></span> <span x-text="fileCount === 1 ? 'file' : 'files'"></span> selected
                                </span>
                            </div>
                        </div>
                    </div>
                    @error('visa_files') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror

                    @if(!empty($visa_files))
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">New Files:</h4>
                        <ul class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($visa_files as $index => $file)
                            <li class="py-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $file->getClientOriginalName() }}</span>
                                </div>
                                <button type="button" wire:click="removeFile('visa_files', {{ $index }})" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(count($visaMedia) > 0)
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Files:</h4>
                        <ul class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($visaMedia as $media)
                            <li class="py-2 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $media['file_name'] }}</span>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ $media['url'] }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <button type="button" wire:click="deleteMedia({{ $media['id'] }})" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('tenants.table') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Update Tenant
                </button>
            </div>
        </form>
    </div>
</div>
