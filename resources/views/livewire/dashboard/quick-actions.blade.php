<div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 h-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Common tasks & alerts</p>
        </div>
        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
    </div>

    <!-- Alerts Section -->
    @if(count($alerts) > 0)
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Alerts & Notifications</h4>
            <div class="space-y-3">
                @foreach($alerts as $alert)
                    <div class="flex items-start p-3 rounded-lg border-l-4 
                        {{ $alert['color'] === 'yellow' ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-400' : '' }}
                        {{ $alert['color'] === 'red' ? 'bg-red-50 dark:bg-red-900/20 border-red-400' : '' }}
                        {{ $alert['color'] === 'blue' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-400' : '' }}
                        {{ $alert['color'] === 'orange' ? 'bg-orange-50 dark:bg-orange-900/20 border-orange-400' : '' }}
                    ">
                        <div class="flex-shrink-0 mr-3">
                            <svg class="w-5 h-5 
                                {{ $alert['color'] === 'yellow' ? 'text-yellow-600 dark:text-yellow-400' : '' }}
                                {{ $alert['color'] === 'red' ? 'text-red-600 dark:text-red-400' : '' }}
                                {{ $alert['color'] === 'blue' ? 'text-blue-600 dark:text-blue-400' : '' }}
                                {{ $alert['color'] === 'orange' ? 'text-orange-600 dark:text-orange-400' : '' }}
                            " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getIcon($alert['icon']) }}"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h5 class="text-sm font-medium 
                                {{ $alert['color'] === 'yellow' ? 'text-yellow-800 dark:text-yellow-200' : '' }}
                                {{ $alert['color'] === 'red' ? 'text-red-800 dark:text-red-200' : '' }}
                                {{ $alert['color'] === 'blue' ? 'text-blue-800 dark:text-blue-200' : '' }}
                                {{ $alert['color'] === 'orange' ? 'text-orange-800 dark:text-orange-200' : '' }}
                            ">
                                {{ $alert['title'] }}
                            </h5>
                            <p class="text-sm 
                                {{ $alert['color'] === 'yellow' ? 'text-yellow-700 dark:text-yellow-300' : '' }}
                                {{ $alert['color'] === 'red' ? 'text-red-700 dark:text-red-300' : '' }}
                                {{ $alert['color'] === 'blue' ? 'text-blue-700 dark:text-blue-300' : '' }}
                                {{ $alert['color'] === 'orange' ? 'text-orange-700 dark:text-orange-300' : '' }}
                            ">
                                {{ $alert['message'] }}
                            </p>
                            <a href="{{ route($alert['route']) }}" class="inline-flex items-center mt-2 text-sm font-medium 
                                {{ $alert['color'] === 'yellow' ? 'text-yellow-800 dark:text-yellow-200 hover:text-yellow-900 dark:hover:text-yellow-100' : '' }}
                                {{ $alert['color'] === 'red' ? 'text-red-800 dark:text-red-200 hover:text-red-900 dark:hover:text-red-100' : '' }}
                                {{ $alert['color'] === 'blue' ? 'text-blue-800 dark:text-blue-200 hover:text-blue-900 dark:hover:text-blue-100' : '' }}
                                {{ $alert['color'] === 'orange' ? 'text-orange-800 dark:text-orange-200 hover:text-orange-900 dark:hover:text-orange-100' : '' }}
                            ">
                                {{ $alert['action'] }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Quick Actions Grid -->
    <div>
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h4>
        <div class="grid grid-cols-2 gap-3">
            @foreach($quickActions as $action)
                <a href="{{ route($action['route']) }}" 
                   class="group flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition-transform
                        {{ $action['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30' : '' }}
                        {{ $action['color'] === 'green' ? 'bg-green-100 dark:bg-green-900/30' : '' }}
                        {{ $action['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900/30' : '' }}
                        {{ $action['color'] === 'indigo' ? 'bg-indigo-100 dark:bg-indigo-900/30' : '' }}
                        {{ $action['color'] === 'pink' ? 'bg-pink-100 dark:bg-pink-900/30' : '' }}
                        {{ $action['color'] === 'yellow' ? 'bg-yellow-100 dark:bg-yellow-900/30' : '' }}
                    ">
                        <svg class="w-5 h-5 
                            {{ $action['color'] === 'blue' ? 'text-blue-600 dark:text-blue-400' : '' }}
                            {{ $action['color'] === 'green' ? 'text-green-600 dark:text-green-400' : '' }}
                            {{ $action['color'] === 'purple' ? 'text-purple-600 dark:text-purple-400' : '' }}
                            {{ $action['color'] === 'indigo' ? 'text-indigo-600 dark:text-indigo-400' : '' }}
                            {{ $action['color'] === 'pink' ? 'text-pink-600 dark:text-pink-400' : '' }}
                            {{ $action['color'] === 'yellow' ? 'text-yellow-600 dark:text-yellow-400' : '' }}
                        " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getIcon($action['icon']) }}"></path>
                        </svg>
                    </div>
                    <h5 class="text-sm font-medium text-gray-900 dark:text-white text-center mb-1">
                        {{ $action['title'] }}
                    </h5>
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                        {{ $action['description'] }}
                    </p>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">System Status</h4>
        <div class="grid grid-cols-2 gap-4 text-center">
            <div class="p-2 bg-gray-50 dark:bg-gray-800/50 rounded">
                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['pending_receipts'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Pending Receipts</div>
            </div>
            <div class="p-2 bg-gray-50 dark:bg-gray-800/50 rounded">
                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['vacant_properties'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">Vacant Properties</div>
            </div>
        </div>
    </div>
</div>