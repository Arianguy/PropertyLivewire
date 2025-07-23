<div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 h-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Property Portfolio</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Overview & Status</p>
        </div>
        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <!-- Total Properties -->
        <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg">
            <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $metrics['total_properties'] }}</div>
            <div class="text-sm text-blue-600 dark:text-blue-400">Total Properties</div>
        </div>

        <!-- Occupancy Rate -->
        <div class="text-center p-4 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg">
            <div class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $metrics['occupancy_rate'] }}%</div>
            <div class="text-sm text-green-600 dark:text-green-400">Occupancy Rate</div>
        </div>
    </div>

    <!-- Occupancy Breakdown -->
    <div class="mb-6">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Occupancy Status</h4>
        <div class="space-y-3">
            <!-- Occupied -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Occupied</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-900 dark:text-white mr-2">{{ $metrics['occupied_properties'] }}</span>
                    <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $metrics['occupancy_rate'] }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Vacant -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-400 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Vacant</span>
                </div>
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-900 dark:text-white mr-2">{{ $metrics['vacant_properties'] }}</span>
                    <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gray-400 h-2 rounded-full" style="width: {{ 100 - $metrics['occupancy_rate'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- <!-- Property Status Distribution -->
        @if(!empty($metrics['properties_by_status']))
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Property Status</h4>
            <div class="space-y-2">
                @foreach($metrics['properties_by_status'] as $status => $count)
                    @php
                        $percentage = $metrics['total_properties'] > 0 ? ($count / $metrics['total_properties']) * 100 : 0;
                        $statusColor = match($status) {
                            'OCCUPIED' => 'bg-green-500',
                            'VACANT' => 'bg-gray-400',
                            'MAINTENANCE' => 'bg-yellow-500',
                            'UNAVAILABLE' => 'bg-red-500',
                            default => 'bg-blue-500'
                        };
                    @endphp
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <div class="w-2 h-2 {{ $statusColor }} rounded-full mr-2"></div>
                            <span class="text-gray-700 dark:text-gray-300 capitalize">{{ strtolower($status) }}</span>
                        </div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif --}}

    <!-- Alerts -->
    @if($metrics['expiring_contracts'] > 0)
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3 mb-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                    {{ $metrics['expiring_contracts'] }} contract{{ $metrics['expiring_contracts'] > 1 ? 's' : '' }} expiring soon
                </p>
                <p class="text-xs text-yellow-600 dark:text-yellow-400">Within next 30 days</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Properties -->
    @if($metrics['recent_properties']->count() > 0)
    <div>
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Recent Additions</h4>
        <div class="space-y-2">
            @foreach($metrics['recent_properties'] as $property)
                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800/50 rounded">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $property->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $property->community }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $property->status === 'OCCUPIED' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                        {{ $property->status }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('properties.create') }}" class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Property
            </a>
            <a href="{{ route('properties.table') }}" class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                View All
            </a>
        </div>
    </div>
</div>
