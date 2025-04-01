<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Edit Owner</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update owner information and documents</p>
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
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update or add new identification documents</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Passport Documents Section -->
                        <div>
                            <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Passport Copies</h4>

                            <!-- Existing Passport Files -->
                            @if(count($passportMedia) > 0)
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Current Files:</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($passportMedia as $media)
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-700 flex flex-col">
                                                <div class="flex items-start mb-2">
                                                    <div class="flex items-center w-full">
                                                        @if(in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                                                            <img src="{{ route('media.thumbnail', ['id' => $media->id, 'conversion' => 'thumb']) }}" alt="{{ $media->file_name }}" class="h-12 w-12 object-cover rounded mr-2">
                                                        @else
                                                            <svg class="h-12 w-12 text-gray-400 dark:text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                            </svg>
                                                        @endif
                                                        <div class="overflow-hidden">
                                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $media->file_name }}</span>
                                                            <span class="block text-xs text-gray-500 dark:text-gray-400">{{ number_format($media->size / 1024, 2) }} KB</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between items-center mt-auto">
                                                    <a href="#" x-data @click.prevent="$dispatch('open-modal', { url: '{{ route('media.show', ['id' => $media->id]) }}', filename: '{{ $media->file_name }}' })" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">View</a>
                                                    <button type="button" wire:click="deleteMedia({{ $media->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm">Delete</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload New Passport Files -->
                            <div class="mt-2">
                                <label for="passportFiles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload New Files</label>
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
                        </div>

                        <!-- EID Documents Section -->
<div>
                            <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">EID Copies</h4>

                            <!-- Existing EID Files -->
                            @if(count($eidMedia) > 0)
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Current Files:</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($eidMedia as $media)
                                            <div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 bg-gray-50 dark:bg-gray-700 flex flex-col">
                                                <div class="flex items-start mb-2">
                                                    <div class="flex items-center w-full">
                                                        @if(in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                                                            <img src="{{ route('media.thumbnail', ['id' => $media->id, 'conversion' => 'thumb']) }}" alt="{{ $media->file_name }}" class="h-12 w-12 object-cover rounded mr-2">
                                                        @else
                                                            <svg class="h-12 w-12 text-gray-400 dark:text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                            </svg>
                                                        @endif
                                                        <div class="overflow-hidden">
                                                            <span class="block text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $media->file_name }}</span>
                                                            <span class="block text-xs text-gray-500 dark:text-gray-400">{{ number_format($media->size / 1024, 2) }} KB</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between items-center mt-auto">
                                                    <a href="#" x-data @click.prevent="$dispatch('open-modal', { url: '{{ route('media.show', ['id' => $media->id]) }}', filename: '{{ $media->file_name }}' })" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">View</a>
                                                    <button type="button" wire:click="deleteMedia({{ $media->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm">Delete</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload New EID Files -->
                            <div class="mt-2">
                                <label for="eidFiles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload New Files</label>
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
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('owners.table') }}" class="bg-white dark:bg-gray-800 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Owner
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div
        x-data="{
            show: false,
            url: '',
            filename: '',
            isImage: false,
            isPdf: false
        }"
        @open-modal.window="
            show = true;
            url = $event.detail.url;
            filename = $event.detail.filename;
            isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(filename.split('.').pop().toLowerCase());
            isPdf = filename.split('.').pop().toLowerCase() === 'pdf';
        "
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" x-show="show" @click="show = false"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden max-w-5xl w-full max-h-[80vh] flex flex-col" x-show="show" @click.away="show = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Modal Header -->
                <div class="bg-gray-50 dark:bg-gray-700 py-3 px-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate" x-text="filename"></h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-grow overflow-auto p-4 flex items-center justify-center">
                    <!-- Image preview -->
                    <template x-if="isImage">
                        <img :src="url" :alt="filename" class="max-w-full max-h-[60vh] object-contain">
                    </template>

                    <!-- PDF preview -->
                    <template x-if="isPdf">
                        <iframe :src="url" class="w-full h-[60vh]" frameborder="0"></iframe>
                    </template>

                    <!-- Other file types -->
                    <template x-if="!isImage && !isPdf">
                        <div class="text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-700 dark:text-gray-300">This file type cannot be previewed.</p>
                            <a :href="url.replace(/\/media\/(\d+)/, '/media/$1/download')" class="inline-flex mt-4 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Download File
                            </a>
                        </div>
                    </template>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 dark:bg-gray-700 py-3 px-4 border-t border-gray-200 dark:border-gray-600 flex justify-end">
                    <a :href="url.replace(/\/media\/(\d+)/, '/media/$1/download')" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </a>
                    <button @click="show = false" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
