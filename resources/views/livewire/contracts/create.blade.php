<div class="mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Create Contract</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Create a new lease contract for your property.
            </p>
        </div>
        <div class="flex-shrink-0">
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
                <!-- Contract Number -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Contract Number</label>
                    <div class="mt-2">
                        <input type="text" wire:model="name" id="name" readonly tabindex="-1" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-500 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500 cursor-default pointer-events-none">
                    </div>
                    @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Tenant Selection -->
                <div class="sm:col-span-2" x-data="{ open: @entangle('show_tenant_dropdown') }">
                    <label for="tenant_search" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Tenant</label>
                    <div class="mt-2 relative">
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="tenant_search" 
                                @focus="$wire.show_tenant_dropdown = true" 
                                @click.away="$wire.show_tenant_dropdown = false"
                                id="tenant_search" 
                                placeholder="Search tenants..." 
                                class="block w-full rounded-md border-0 py-1.5 px-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:focus:ring-indigo-500"
                            >
                            @if($tenant_id)
                                <button 
                                    type="button" 
                                    wire:click="clearTenantSelection" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        
                        <!-- Dropdown -->
                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                        >
                            @if($this->filteredTenants->count() > 0)
                                @foreach($this->filteredTenants as $tenant)
                                    <div 
                                        wire:click="selectTenant({{ $tenant->id }}, '{{ $tenant->name }}')"
                                        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500 {{ $tenant_id == $tenant->id ? 'bg-indigo-600 text-white' : 'text-gray-900 dark:text-gray-100' }}"
                                    >
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ $tenant->name }}</span>
                                        </div>
                                        @if($tenant_id == $tenant->id)
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-4">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-700 dark:text-gray-300">
                                    No tenants found.
                                </div>
                            @endif
                        </div>
                    </div>
                    @error('tenant_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Property Selection -->
                <div class="sm:col-span-2" x-data="{ open: @entangle('show_property_dropdown') }">
                    <label for="property_search" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Property</label>
                    <div class="mt-2 relative">
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="property_search" 
                                @focus="$wire.show_property_dropdown = true" 
                                @click.away="$wire.show_property_dropdown = false"
                                id="property_search" 
                                placeholder="Search properties..." 
                                class="block w-full rounded-md border-0 py-1.5 px-3 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:focus:ring-indigo-500"
                            >
                            @if($property_id)
                                <button 
                                    type="button" 
                                    wire:click="clearPropertySelection" 
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        
                        <!-- Dropdown -->
                        <div 
                            x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                        >
                            @if($this->filteredProperties->count() > 0)
                                @foreach($this->filteredProperties as $property)
                                    <div 
                                        wire:click="selectProperty({{ $property->id }}, '{{ $property->name }}')"
                                        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500 {{ $property_id == $property->id ? 'bg-indigo-600 text-white' : 'text-gray-900 dark:text-gray-100' }}"
                                    >
                                        <div class="flex flex-col">
                                            <span class="font-medium">{{ $property->name }}</span>
                                            @if($property->address)
                                                <span class="text-sm opacity-75">{{ $property->address }}</span>
                                            @endif
                                        </div>
                                        @if($property_id == $property->id)
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-4">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-700 dark:text-gray-300">
                                    No vacant properties found.
                                </div>
                            @endif
                        </div>
                    </div>
                    @error('property_id') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Contract Start Date -->
                <div class="sm:col-span-2">
                    <label for="cstart" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Contract Start Date</label>
                    <div class="mt-2">
                        <input type="date" wire:model="cstart" id="cstart" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500 dark:[color-scheme:dark]">
                    </div>
                    @error('cstart') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Contract End Date -->
                <div class="sm:col-span-2">
                    <label for="cend" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Contract End Date</label>
                    <div class="mt-2">
                        <input type="date" wire:model="cend" id="cend" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:placeholder:text-gray-500 dark:focus:ring-indigo-500 dark:[color-scheme:dark]">
                    </div>
                    @error('cend') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
                    @error('amount') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
                    @error('sec_amt') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Ejari Status -->
                <div class="sm:col-span-2">
                    <label for="ejari" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Ejari Status</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input id="ejari-yes" wire:model="ejari" type="radio" value="YES" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-indigo-600">
                            <label for="ejari-yes" class="ml-2 block text-sm text-gray-900 dark:text-gray-200">Yes</label>
                        </div>
                        <div class="flex items-center">
                            <input id="ejari-no" wire:model="ejari" type="radio" value="NO" class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:border-gray-600 dark:bg-gray-700 dark:checked:bg-indigo-600">
                            <label for="ejari-no" class="ml-2 block text-sm text-gray-900 dark:text-gray-200">No</label>
                        </div>
                    </div>
                    @error('ejari') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
                        class="mt-2"
                    >
                        <div
                            x-on:dragover.prevent="isDropping = true"
                            x-on:dragleave.prevent="isDropping = false"
                            x-on:drop.prevent="isDropping = false; handleFileDrop($event)"
                            class="flex justify-center rounded-lg border border-dashed border-gray-900/25 dark:border-gray-100/25 px-6 py-6"
                            :class="{ 'bg-indigo-50 dark:bg-indigo-900/10 border-indigo-600 dark:border-indigo-400': isDropping }"
                            onclick="document.getElementById('cont_copy-input-create').click()"
                        >
                            <div class="text-center">
                                <flux:icon name="cloud-arrow-up" class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                                <div class="mt-3 flex text-sm leading-6 text-gray-600 dark:text-gray-400">
                                    <label for="cont_copy-input-create" class="relative cursor-pointer rounded-md bg-white dark:bg-gray-800 font-semibold text-indigo-600 dark:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 dark:focus-within:ring-offset-gray-800 hover:text-indigo-500 dark:hover:text-indigo-300">
                                        <span>Upload files</span>
                                        <input id="cont_copy-input-create" wire:model="cont_copy" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png" multiple>
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
                                <div wire:key="temp-contract-attachment-create-{{ $index }}" class="flex items-center justify-between py-2 px-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-600">
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

                 <!-- Placeholder for Existing Attachments column -->
                 <div class="sm:col-span-2">
                    <h4 class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200"> </h4>
                     <div class="mt-2">
                        <!-- Content for this column can be added if needed for create form, or kept empty for layout -->
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700/50 flex items-center justify-end gap-x-3">
                <a href="{{ route('contracts.table') }}" class="rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        Create Contract
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Creating...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
