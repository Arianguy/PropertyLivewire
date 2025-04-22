<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-gray-100">Edit Property</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update property information and documents</p>
        </div>
        <a href="{{ route('properties.table') }}" class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700">
            <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Properties
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
        <div class="p-6 sm:p-8">
            <form wire:submit="save" class="space-y-8">
                <!-- Basic Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Basic Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Property Name</label>
                            <div class="mt-1">
                                <input type="text" id="name" wire:model="name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="class" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Property Class</label>
                            <div class="mt-1">
                                <select id="class" wire:model="class" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                    <option value="">Select Class</option>
                                    <option value="1 BHK">1 BHK</option>
                                    <option value="2 BHK">2 BHK</option>
                                    <option value="STUDIO">STUDIO</option>
                                    <option value="WH">WAREHOUSE</option>
                                    <option value="OFFICE">OFFICE</option>
                                </select>
                            </div>
                            @error('class') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Property Type</label>
                            <div class="mt-1">
                                <select id="type" wire:model="type" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                    <option value="">Select Type</option>
                                    <option value="Residential">Residential</option>
                                    <option value="Commercial">Commercial</option>
                                    <option value="Land">Land</option>
                                </select>
                            </div>
                            @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                    <!-- Property Details Card -->
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Property Details</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <label for="purchase_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purchase Date</label>
                                <div class="mt-1">
                                    <input type="date" id="purchase_date" wire:model="purchase_date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                </div>
                                @error('purchase_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                        <div class="sm:col-span-2">
                            <label for="title_deed_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title Deed Number</label>
                            <div class="mt-1">
                                <input type="text" id="title_deed_no" wire:model="title_deed_no" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('title_deed_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="mortgage_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mortgage Status</label>
                            <div class="mt-1">
                                <select id="mortgage_status" wire:model="mortgage_status" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                    <option value="None">None</option>
                                    <option value="Mortgaged">Mortgaged</option>
                                </select>
                            </div>
                            @error('mortgage_status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Location Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="community" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Community</label>
                            <div class="mt-1">
                                <input type="text" id="community" wire:model="community" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('community') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="plot_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plot Number</label>
                            <div class="mt-1">
                                <input type="number" id="plot_no" wire:model="plot_no" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('plot_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-1">
                            <label for="bldg_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Building Number</label>
                            <div class="mt-1">
                                <input type="number" id="bldg_no" wire:model="bldg_no" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('bldg_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="bldg_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Building Name</label>
                            <div class="mt-1">
                                <input type="text" id="bldg_name" wire:model="bldg_name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('bldg_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="property_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Property Number</label>
                            <div class="mt-1">
                                <input type="text" id="property_no" wire:model="property_no" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('property_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="floor_detail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Floor Detail</label>
                            <div class="mt-1">
                                <input type="text" id="floor_detail" wire:model="floor_detail" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('floor_detail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Area Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Area Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="suite_area" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Suite Area</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" id="suite_area" wire:model="suite_area" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('suite_area') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="balcony_area" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Balcony Area</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" id="balcony_area" wire:model="balcony_area" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('balcony_area') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="area_sq_mter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Area (sq. meters)</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" id="area_sq_mter" wire:model="area_sq_mter" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('area_sq_mter') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="common_area" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Common Area</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" id="common_area" wire:model="common_area" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('common_area') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="area_sq_feet" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Area (sq. feet)</label>
                            <div class="mt-1">
                                <input type="number" step="0.01" id="area_sq_feet" wire:model="area_sq_feet" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('area_sq_feet') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Financial & Additional Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Financial & Additional Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="owner_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Owner</label>
                            <div class="mt-1">
                                <select id="owner_id" wire:model="owner_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                    <option value="">Select Owner</option>
                                    @foreach($owners as $owner)
                                        <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('owner_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="purchase_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purchase Value</label>
                            <div class="mt-1">
                                <input type="number" id="purchase_value" wire:model="purchase_value" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('purchase_value') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <div class="mt-1">
                                <select id="status" wire:model="status" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                                    <option value="VACANT">VACANT</option>
                                    <option value="LEASED">LEASED</option>
                                    <option value="MAINTENANCE">MAINTENANCE</option>
                                </select>
                            </div>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="dewa_premise_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DEWA Premise No.</label>
                            <div class="mt-1">
                                <input type="number" id="dewa_premise_no" wire:model="dewa_premise_no" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('dewa_premise_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="dewa_account_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">DEWA Account No.</label>
                            <div class="mt-1">
                                <input type="number" id="dewa_account_no" wire:model="dewa_account_no" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                            </div>
                            @error('dewa_account_no') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Document Uploads Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Document Uploads</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update or add new property documents</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Deed Documents Section -->
<div>
                            <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Deed & Sales Documents</h4>

                            <!-- Existing Deed Files -->
                            @if(count($deedMedia) > 0)
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Current Files:</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($deedMedia as $media)
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

                            <!-- Upload New Deed Files -->
                            <div class="mt-2">
                                <label for="deedFiles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload New Files</label>
                                <div
                                    x-data="{
                                        isDropping: false,
                                        isUploading: false,
                                        progress: 0,
                                        handleDrop(e) {
                                            e.preventDefault();
                                            isDropping = false;
                                            @this.uploadMultiple('deedFiles', e.dataTransfer.files);
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
                                            <label for="deedFiles" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 dark:focus-within:ring-offset-gray-800">
                                                <span>Upload files</span>
                                                <input id="deedFiles" type="file" wire:model="deedFiles" multiple class="sr-only">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, PDF up to 10MB each</p>
                                    </div>
                                </div>
                                @error('deedFiles') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                                @if($uploadedDeeds && $deedFiles && count($deedFiles) > 0)
                                    <div class="mt-2">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Selected files:</p>
                                        <ul class="mt-1 text-sm text-gray-500 dark:text-gray-400 list-disc pl-5">
                                            @foreach($deedFiles as $file)
                                                <li>{{ $file->getClientOriginalName() }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visibility Toggle -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_visible" wire:model="is_visible" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:border-gray-600">
                        <label for="is_visible" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                            Visible to Users
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">When enabled, this property will be visible to all users with appropriate permissions.</p>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('properties.table') }}" class="bg-white dark:bg-gray-800 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Property
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
