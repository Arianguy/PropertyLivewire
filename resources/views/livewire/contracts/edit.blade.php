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
            <div class="space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
                <div>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contract Number
                            </label>
                            <div class="mt-1">
                                <input type="text" value="{{ $contract->name }}" id="name" readonly class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="tenant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tenant
                            </label>
                            <div class="mt-1">
                                <select wire:model="tenant_id" id="tenant_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                    <option value="">Select a tenant</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('tenant_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="property_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Property
                            </label>
                            <div class="mt-1">
                                <select wire:model="property_id" id="property_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                    <option value="">Select a property</option>
                                    @foreach($properties as $property)
                                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('property_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Rental Amount
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" wire:model="amount" id="amount" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="0.00">
                            </div>
                            @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="sec_amt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Security Deposit
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" wire:model="sec_amt" id="sec_amt" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="0.00">
                            </div>
                            @error('sec_amt') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="cstart" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Start Date
                            </label>
                            <div class="mt-1">
                                <input type="date" wire:model="cstart" id="cstart" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('cstart') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="cend" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                End Date
                            </label>
                            <div class="mt-1">
                                <input type="date" wire:model="cend" id="cend" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('cend') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <fieldset>
                                <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Ejari Status</legend>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input id="ejari-yes" wire:model="ejari" type="radio" value="YES" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:focus:ring-offset-gray-800">
                                        <label for="ejari-yes" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="ejari-no" wire:model="ejari" type="radio" value="NO" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:focus:ring-offset-gray-800">
                                        <label for="ejari-no" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            @error('ejari') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <fieldset>
                                <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Contract Status</legend>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input id="validity-yes" wire:model="validity" type="radio" value="YES" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:focus:ring-offset-gray-800">
                                        <label for="validity-yes" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Active
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="validity-no" wire:model="validity" type="radio" value="NO" class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-600 dark:border-gray-700 dark:focus:ring-offset-gray-800">
                                        <label for="validity-no" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Inactive
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                            @error('validity') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-6">
                            <label for="cont_copy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contract Documents
                            </label>
                            <div class="mt-1">
                                <input type="file" wire:model="cont_copy" id="cont_copy" multiple class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900 dark:file:text-primary-300 dark:text-gray-100">
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Upload PDF, JPG, JPEG, or PNG files (max 10MB)</p>
                            @error('cont_copy.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                            <div wire:loading wire:target="cont_copy" class="mt-2">
                                <div class="animate-pulse flex space-x-4">
                                    <div class="flex-1 space-y-6 py-1">
                                        <div class="h-2 bg-gray-300 rounded"></div>
                                        <div class="space-y-3">
                                            <div class="grid grid-cols-3 gap-4">
                                                <div class="h-2 bg-gray-300 rounded col-span-2"></div>
                                                <div class="h-2 bg-gray-300 rounded col-span-1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Uploading files...</p>
                            </div>

                            <!-- Existing Documents -->
                            @if(count($media) > 0)
                                <div class="mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Documents</h4>
                                    <ul class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($media as $item)
                                            <li class="py-3 flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        @if(Str::contains($item['mime_type'], 'pdf'))
                                                            <flux:icon name="document-text" class="h-6 w-6 text-gray-500" />
                                                        @else
                                                            <flux:icon name="photo" class="h-6 w-6 text-gray-500" />
                                                        @endif
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['file_name'] }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ human_filesize($item['size']) }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('media.show', $item['id']) }}" target="_blank" class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                                                        <flux:icon name="eye" class="h-4 w-4 mr-1" />
                                                        View
                                                    </a>
                                                    <a href="{{ route('media.download', $item['id']) }}" class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                                                        <flux:icon name="arrow-down-tray" class="h-4 w-4 mr-1" />
                                                        Download
                                                    </a>
                                                    <button type="button" wire:click="deleteMedia({{ $item['id'] }})" wire:confirm="Are you sure you want to delete this document?" class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:ring-gray-700 dark:hover:bg-gray-700">
                                                        <flux:icon name="trash" class="h-4 w-4 mr-1" />
                                                        Delete
                                                    </button>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('contracts.table') }}" class="rounded-md bg-white py-2 px-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center rounded-md bg-primary-600 py-2 px-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        Update Contract
                    </button>
                </div>
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
