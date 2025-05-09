<div>
    <div class="mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Edit Contract #{{ $contract->name }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Modify the contract details and documents.
                </p>
            </div>
            <div class="flex-shrink-0 flex space-x-3">
                <a href="{{ route('contracts.show', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <flux:icon name="eye" class="-ml-0.5 h-5 w-5" />
                    View Contract
                </a>
                <a href="{{ route('contracts.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <svg class="-ml-0.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Contracts
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
            <form wire:submit="save" class="space-y-6 p-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <!-- Tenant Selection -->
                    <div class="sm:col-span-2">
                        <label for="tenant_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Tenant</label>
                        <div class="mt-2">
                            <select wire:model="tenant_id" id="tenant_id" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:focus:ring-indigo-500">
                                <option value="">Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('tenant_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Property Selection -->
                    <div class="sm:col-span-2">
                        <label for="property_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Property</label>
                        <div class="mt-2">
                            <select wire:model="property_id" id="property_id" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:focus:ring-indigo-500">
                                <option value="">Select Property</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('property_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Contract Start Date -->
                    <div class="sm:col-span-2">
                        <label for="cstart" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Contract Start Date</label>
                        <div class="mt-2">
                            <input type="date" wire:model="cstart" id="cstart" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500 dark:[color-scheme:dark]">
                        </div>
                        @error('cstart') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Contract End Date -->
                    <div class="sm:col-span-2">
                        <label for="cend" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Contract End Date</label>
                        <div class="mt-2">
                            <input type="date" wire:model="cend" id="cend" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500 dark:[color-scheme:dark]">
                        </div>
                        @error('cend') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rental Amount -->
                    <div class="sm:col-span-2">
                        <label for="amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Rental Amount</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm"></span>
                            </div>
                            <input type="number" wire:model="amount" id="amount" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500" placeholder="0.00">
                        </div>
                        @error('amount') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <!-- Security Amount -->
                    <div class="sm:col-span-2">
                        <label for="sec_amt" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Security Amount</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 dark:text-gray-400 sm:text-sm"></span>
                            </div>
                            <input type="number" wire:model="sec_amt" id="sec_amt" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500" placeholder="0.00">
                        </div>
                        @error('sec_amt') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <!-- Ejari Number -->
                    <div class="sm:col-span-2">
                        <label for="ejari" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Ejari Number</label>
                        <div class="mt-2">
                            <input type="text" wire:model="ejari" id="ejari" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500">
                        </div>
                        @error('ejari') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Contract Copy Upload -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Contract Documents</label>
                        <div
                            x-data="{
                                isDropping: false,
                                files: @entangle('cont_copy').live,
                                handleFileDrop(e) {
                                    if (e.dataTransfer.files.length > 0) {
                                        const fileList = Array.from(e.dataTransfer.files);
                                        @this.uploadMultiple('cont_copy', fileList,
                                            (uploadedFilename) => { console.log(uploadedFilename + ' uploaded successfully'); },
                                            () => { console.log('Error uploading files'); },
                                            (event) => { console.log('Upload progress: ' + event.detail.progress + '%'); }
                                        );
                                    }
                                }
                            }"
                            x-on:dragover.prevent="isDropping = true"
                            x-on:dragleave.prevent="isDropping = false"
                            x-on:drop.prevent="isDropping = false; handleFileDrop($event)"
                            @media-deleted.window="files = []"
                            class="mt-2"
                        >
                            <div
                                x-on:dragover.prevent="isDropping = true"
                                x-on:dragleave.prevent="isDropping = false"
                                x-on:drop.prevent="isDropping = false; handleFileDrop($event)"
                                class="flex justify-center rounded-lg border border-dashed border-gray-900/25 dark:border-gray-100/25 px-6 py-6"
                                :class="{ 'bg-indigo-50 dark:bg-indigo-900/10 border-indigo-600 dark:border-indigo-400': isDropping }"
                                onclick="document.getElementById('cont_copy-input').click()"
                            >
                                <div class="text-center">
                                    <flux:icon name="cloud-arrow-up" class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                                    <div class="mt-3 flex text-sm leading-6 text-gray-600 dark:text-gray-400">
                                        <label for="cont_copy-input" class="relative cursor-pointer rounded-md bg-white dark:bg-gray-800 font-semibold text-indigo-600 dark:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 dark:focus-within:ring-offset-gray-800 hover:text-indigo-500 dark:hover:text-indigo-300">
                                            <span>Upload files</span>
                                            <input id="cont_copy-input" wire:model="cont_copy" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" multiple>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600 dark:text-gray-400">PDF, JPG, PNG up to 10MB each</p>
                                </div>
                            </div>
                            @error('cont_copy.*') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            @error('cont_copy') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                            <!-- Upload Progress -->
                            <div wire:loading wire:target="cont_copy" class="mt-3 w-full" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Uploading files...</div>
                                <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300 ease-out" style="width: 0%" x-bind:style="`width: ${$wire.uploadProgress || 0}%`" x-text="`${$wire.uploadProgress || 0}%`"></div>
                                </div>
                            </div>

                            <!-- Temporary Uploads Preview -->
                            @if (is_array($cont_copy) && count($cont_copy) > 0 && method_exists(Arr::first($cont_copy), 'temporaryUrl'))
                            <div class="mt-4 space-y-2">
                                <h4 class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">New Attachments:</h4>
                                @foreach ($cont_copy as $index => $attachment)
                                    @if ($attachment && method_exists($attachment, 'temporaryUrl'))
                                    <div wire:key="temp-contract-attachment-{{ $index }}" class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600">
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

                    <!-- Existing Files -->
                    <div class="sm:col-span-2" wire:key="media-container">
                        @if(count($media) > 0)
                        <div class="mt-2 space-y-3">
                            <h4 class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Existing Attachments {{ count($media) > 0 ? '(' . count($media) . ')' : '' }}</h4>
                            @foreach($media as $file)
                            <div wire:key="existing-contract-attachment-{{ $file['id'] }}" class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center space-x-3 truncate">
                                    <flux:icon name="document-text" class="h-5 w-5 text-gray-500 dark:text-gray-400 flex-shrink-0" />
                                    <a href="{{ $file['download_url'] }}" target="_blank" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 hover:underline truncate" title="{{ $file['name'] }}">
                                        {{ Str::limit($file['name'], 35) }}
                                    </a>
                                    <span class="flex-shrink-0 text-xs text-gray-400 dark:text-gray-500">({{ number_format($file['size'] / 1024, 2) }} kb)</span>
                                </div>
                                <button
                                    type="button"
                                    wire:click="deleteMedia({{ $file['id'] }})"
                                    wire:confirm="Are you sure you want to delete this file?"
                                    wire:loading.attr="disabled"
                                    wire:target="deleteMedia({{ $file['id'] }})"
                                    class="ml-3 text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 p-1 rounded-md hover:bg-red-100 dark:hover:bg-red-700/50 transition-colors">
                                    <span wire:loading.remove wire:target="deleteMedia({{ $file['id'] }})">Remove</span>
                                    <span wire:loading wire:target="deleteMedia({{ $file['id'] }})" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-1 h-4 w-4 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Removing...
                                    </span>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="mt-2">
                            <h4 class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Existing Attachments</h4>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 italic">No files uploaded yet.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700/50 flex items-center justify-end gap-x-3">
                    <a href="{{ route('contracts.table') }}" class="rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</a>
                    <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            Save Changes
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@php
function human_filesize($size, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $step = 1024;
    $i = 0;
    while ($size > $step) {
        $size = $size / $step;
        $i++;
    }
    return round($size, $precision) . ' ' . $units[$i];
}
@endphp
