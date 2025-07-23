<div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 h-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activity</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Last 7 days</p>
        </div>
        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Activity List -->
    <div class="space-y-4 max-h-96 overflow-y-auto">
        @forelse($activities as $activity)
            <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center
                        {{ $activity['color'] === 'green' ? 'bg-green-100 dark:bg-green-900/30' : '' }}
                        {{ $activity['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30' : '' }}
                        {{ $activity['color'] === 'yellow' ? 'bg-yellow-100 dark:bg-yellow-900/30' : '' }}
                        {{ $activity['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30' : '' }}
                        {{ $activity['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900/30' : '' }}
                        {{ $activity['color'] === 'gray' ? 'bg-gray-100 dark:bg-gray-700' : '' }}
                    ">
                        <svg class="w-4 h-4 
                            {{ $activity['color'] === 'green' ? 'text-green-600 dark:text-green-400' : '' }}
                            {{ $activity['color'] === 'blue' ? 'text-blue-600 dark:text-blue-400' : '' }}
                            {{ $activity['color'] === 'yellow' ? 'text-yellow-600 dark:text-yellow-400' : '' }}
                            {{ $activity['color'] === 'red' ? 'text-red-600 dark:text-red-400' : '' }}
                            {{ $activity['color'] === 'purple' ? 'text-purple-600 dark:text-purple-400' : '' }}
                            {{ $activity['color'] === 'gray' ? 'text-gray-600 dark:text-gray-400' : '' }}
                        " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getIcon($activity['icon']) }}"></path>
                        </svg>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $activity['title'] }}
                        </p>
                        <div class="flex items-center space-x-2">
                            <!-- Status Badge -->
                            @if(isset($activity['status']))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $activity['color'] === 'green' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                    {{ $activity['color'] === 'blue' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                    {{ $activity['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : '' }}
                                    {{ $activity['color'] === 'red' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : '' }}
                                    {{ $activity['color'] === 'purple' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : '' }}
                                    {{ $activity['color'] === 'gray' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                ">
                                    {{ ucfirst(strtolower($activity['status'])) }}
                                </span>
                            @endif
                            
                            <!-- Time -->
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                        {{ $activity['description'] }}
                    </p>
                    
                    @if(isset($activity['property']))
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $activity['property'] }}
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">No recent activity</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Activity from the last 7 days will appear here</p>
            </div>
        @endforelse
    </div>

    <!-- View All Link -->
    @if(count($activities) > 0)
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="#" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                View all activity
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    @endif
</div>