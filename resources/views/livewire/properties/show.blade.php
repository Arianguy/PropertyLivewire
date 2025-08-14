<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $property->name }}</h1>
                    <p class="text-blue-100 mt-1">Property Details & Management</p>
                </div>
                <div class="flex space-x-3">
                    @if(!$property->is_archived)
                        <a href="{{ route('properties.edit', $property) }}" 
                           class="px-4 py-2 bg-white text-blue-600 rounded-md hover:bg-blue-50 transition-colors duration-200 font-medium">
                            Edit Property
                        </a>
                        <a href="{{ route('properties.sell', $property) }}" 
                           class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-200 font-medium">
                            Sell Property
                        </a>
                    @else
                        <span class="px-4 py-2 bg-gray-600 text-white rounded-md font-medium">
                            Archived Property
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Banner -->
        <div class="px-6 py-3 border-b">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-500">Status:</span>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        @if($property->status === 'VACANT') bg-yellow-100 text-yellow-800
                        @elseif($property->status === 'LEASED') bg-green-100 text-green-800
                        @elseif($property->status === 'MAINTENANCE') bg-blue-100 text-blue-800
                        @elseif($property->status === 'SOLD') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $property->status }}
                    </span>
                    @if($property->is_archived)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                            ARCHIVED
                        </span>
                    @endif
                </div>
                @if($property->is_archived && $property->archived_at)
                    <div class="text-sm text-gray-500">
                        Archived on {{ $property->archived_at->format('M d, Y') }}
                        @if($property->archivedBy)
                            by {{ $property->archivedBy->name }}
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Property Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Property Information</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Property ID</dt>
                            <dd class="text-sm text-gray-900">{{ $property->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Class</dt>
                            <dd class="text-sm text-gray-900">{{ $property->class ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="text-sm text-gray-900">{{ $property->type ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Community</dt>
                            <dd class="text-sm text-gray-900">{{ $property->community ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Plot No</dt>
                            <dd class="text-sm text-gray-900">{{ $property->plot_no ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Building No</dt>
                            <dd class="text-sm text-gray-900">{{ $property->bldg_no ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Building Name</dt>
                            <dd class="text-sm text-gray-900">{{ $property->bldg_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Property No</dt>
                            <dd class="text-sm text-gray-900">{{ $property->property_no ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Floor Detail</dt>
                            <dd class="text-sm text-gray-900">{{ $property->floor_detail ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Suite Area</dt>
                            <dd class="text-sm text-gray-900">{{ $property->suite_area ? number_format($property->suite_area, 2) . ' sq m' : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Area (Sq Feet)</dt>
                            <dd class="text-sm text-gray-900">{{ $property->area_sq_feet ? number_format($property->area_sq_feet, 2) . ' sq ft' : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Purchase Date</dt>
                            <dd class="text-sm text-gray-900">{{ $property->purchase_date ? $property->purchase_date->format('M d, Y') : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Purchase Value</dt>
                            <dd class="text-sm text-gray-900">{{ $property->purchase_value ? number_format($property->purchase_value) : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Title Deed No</dt>
                            <dd class="text-sm text-gray-900">{{ $property->title_deed_no ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            @if($property->status === 'SOLD')
                <!-- Sale Information -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                        <h2 class="text-lg font-semibold text-red-800 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                            </svg>
                            Sale Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sale Date</dt>
                                <dd class="text-sm text-gray-900">{{ $property->sale_date ? $property->sale_date->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sale Price</dt>
                                <dd class="text-sm text-gray-900">{{ $property->sale_price ? number_format($property->sale_price, 2) : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Buyer Name</dt>
                                <dd class="text-sm text-gray-900">{{ $property->buyer_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Profit/Loss</dt>
                                @php
                                    $profitLoss = $property->sale_price - $property->purchase_value;
                                @endphp
                                <dd class="text-sm font-semibold {{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $profitLoss >= 0 ? '+' : '' }}{{ number_format($profitLoss, 2) }}
                                </dd>
                            </div>
                            @if($property->sale_notes)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Sale Notes</dt>
                                <dd class="text-sm text-gray-900">{{ $property->sale_notes }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Owner Information -->
            @if($property->owner)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Owner Information</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="text-sm text-gray-900">{{ $property->owner->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $property->owner->email ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="text-sm text-gray-900">{{ $property->owner->phone ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            @endif

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Contracts</dt>
                            <dd class="text-sm text-gray-900">{{ $property->contracts->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Active Contracts</dt>
                            <dd class="text-sm text-gray-900">{{ $property->contracts->where('status', 'Active')->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">DEWA Premise No</dt>
                            <dd class="text-sm text-gray-900">{{ $property->dewa_premise_no ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">DEWA Account No</dt>
                            <dd class="text-sm text-gray-900">{{ $property->dewa_account_no ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('properties.table') }}" 
                       class="w-full inline-flex justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Back to Properties
                    </a>
                    @if(!$property->is_archived)
                        <a href="{{ route('properties.edit', $property) }}" 
                           class="w-full inline-flex justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Edit Property
                        </a>
                        <a href="{{ route('properties.sell', $property) }}" 
                           class="w-full inline-flex justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            Sell Property
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>