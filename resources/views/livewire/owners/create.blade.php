<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
<div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Create Owner</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Add a new property owner to the system</p>
        </div>
        <a href="{{ route('owners.table') }}" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700">
            <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Owners
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
        <div class="p-6 sm:p-8">
            <form wire:submit="save" class="space-y-8">
                <!-- Personal Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Personal Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <div class="mt-1">
                                <input type="text" id="name" wire:model="name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="eid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">EID Number</label>
                            <div class="mt-1">
                                <input type="text" id="eid" wire:model="eid" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('eid') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="eidexp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">EID Expiry Date</label>
                            <div class="mt-1">
                                <input type="date" id="eidexp" wire:model="eidexp" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('eidexp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nationality</label>
                            <div class="mt-1">
                                <input type="text" id="nationality" wire:model="nationality" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('nationality') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contact Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <div class="mt-1">
                                <input type="email" id="email" wire:model="email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number</label>
                            <div class="mt-1">
                                <input type="text" id="mobile" wire:model="mobile" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('mobile') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="nakheelno" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nakheel Number</label>
                            <div class="mt-1">
                                <input type="text" id="nakheelno" wire:model="nakheelno" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('nakheelno') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                            <div class="mt-1">
                                <textarea id="address" wire:model="address" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700"></textarea>
                            </div>
                            @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Uploads Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Document Uploads</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload scanned copies of identification documents</p>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="passportFiles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Copies</label>
                            <div
                                x-data="{
                                    isDropping: false,
                                    isUploading: false,
                                    progress: 0,
                                    handleDrop(e) {
                                        e.preventDefault();
                                        isDropping = false;
                                        @this.uploadMultiple('passportFiles', e.dataTransfer.files);
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
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="passportFiles" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 dark:focus-within:ring-offset-gray-800">
                                            <span>Upload files</span>
                                            <input id="passportFiles" type="file" wire:model="passportFiles" multiple class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB each</p>
                                </div>
                            </div>
                            @error('passportFiles') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                            @if($uploadedPassports && $passportFiles && count($passportFiles) > 0)
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected files:</p>
                                    <ul class="mt-1 text-sm text-gray-500 dark:text-gray-400 list-disc pl-5">
                                        @foreach($passportFiles as $file)
                                            <li>{{ $file->getClientOriginalName() }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="sm:col-span-3">
                            <label for="eidFiles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">EID Copies</label>
                            <div
                                x-data="{
                                    isDropping: false,
                                    isUploading: false,
                                    progress: 0,
                                    handleDrop(e) {
                                        e.preventDefault();
                                        isDropping = false;
                                        @this.uploadMultiple('eidFiles', e.dataTransfer.files);
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
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="eidFiles" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 dark:focus-within:ring-offset-gray-800">
                                            <span>Upload files</span>
                                            <input id="eidFiles" type="file" wire:model="eidFiles" multiple class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB each</p>
                                </div>
                            </div>
                            @error('eidFiles') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                            @if($uploadedEids && $eidFiles && count($eidFiles) > 0)
                                <div class="mt-2">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected files:</p>
                                    <ul class="mt-1 text-sm text-gray-500 dark:text-gray-400 list-disc pl-5">
                                        @foreach($eidFiles as $file)
                                            <li>{{ $file->getClientOriginalName() }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('owners.table') }}" class="bg-white dark:bg-gray-800 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save Owner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
