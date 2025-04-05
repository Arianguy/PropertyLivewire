<div>
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Contract #{{ $contract->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Modify the contract details and documents.
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('contracts.show', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <flux:icon name="eye" class="-ml-0.5 h-5 w-5" />
                    View Contract
                </a>
                <a href="{{ route('contracts.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                    Back to Contracts
                </a>
            </div>
        </div>

        <form wire:submit="save" class="mt-6 space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
            <div class="space-y-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">
                    <!-- Tenant Selection -->
                    <div>
                        <label for="tenant_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Tenant</label>
                        <div class="mt-2">
                            <select wire:model="tenant_id" id="tenant_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                <option value="">Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('tenant_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Property Selection -->
                    <div>
                        <label for="property_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Property</label>
                        <div class="mt-2">
                            <select wire:model="property_id" id="property_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                <option value="">Select Property</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('property_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Contract Start Date -->
                    <div>
                        <label for="cstart" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Contract Start Date</label>
                        <div class="mt-2">
                            <input type="date" wire:model="cstart" id="cstart" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                        </div>
                        @error('cstart') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Contract End Date -->
                    <div>
                        <label for="cend" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Contract End Date</label>
                        <div class="mt-2">
                            <input type="date" wire:model="cend" id="cend" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                        </div>
                        @error('cend') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Rental Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Rental Amount</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" wire:model="amount" id="amount" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="0.00">
                        </div>
                        @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Security Amount -->
                    <div>
                        <label for="sec_amt" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Security Amount</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" wire:model="sec_amt" id="sec_amt" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="0.00">
                        </div>
                        @error('sec_amt') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Ejari Number -->
                    <div>
                        <label for="ejari" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Ejari Number</label>
                        <div class="mt-2">
                            <input type="text" wire:model="ejari" id="ejari" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                        </div>
                        @error('ejari') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Contract Copy Upload -->
                    <div class="col-span-full">
                        <label for="cont_copy" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Contract Documents</label>
                        <div
                            x-data="{
                                isDropping: false,
                                files: [],
                                handleFileDrop(e) {
                                    if (e.dataTransfer.files.length > 0) {
                                        const fileList = Array.from(e.dataTransfer.files);
                                        @this.uploadMultiple('cont_copy', fileList);
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
                                <label for="cont_copy" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500"
                                    :class="{ 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/10': isDropping }">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <div class="flex flex-col items-center" x-show="files.length === 0">
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PDF files up to 10MB</p>
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
                                        id="cont_copy"
                                        wire:model="cont_copy"
                                        type="file"
                                        class="hidden"
                                        accept=".pdf"
                                        multiple
                                        x-on:change="handleFileSelect($event)"
                                    >
                                </label>
                            </div>
                        </div>
                        @error('cont_copy.*') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>

                    <!-- Existing Files -->
                    @if(count($media) > 0)
                    <div class="col-span-full">
                        <h3 class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">Uploaded Files</h3>
                        <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                            @foreach($media as $file)
                            <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                <div class="flex w-0 flex-1 items-center">
                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <span class="truncate font-medium">{{ $file['name'] }}</span>
                                        <span class="flex-shrink-0 text-gray-400">{{ number_format($file['size'] / 1024, 2) }} kb</span>
                                    </div>
                                </div>
                                <div class="ml-4 flex flex-shrink-0 space-x-4">
                                    <a href="{{ $file['download_url'] }}" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                    <button type="button" wire:click="deleteMedia({{ $file['id'] }})" class="font-medium text-red-600 hover:text-red-500">Delete</button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('contracts.table') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>
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
