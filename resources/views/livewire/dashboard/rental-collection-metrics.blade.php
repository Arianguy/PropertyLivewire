<div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 h-full">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Rental Collection Metrics</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $currentMonth->format('F Y') }} Performance</p>
        </div>
        <button 
            wire:click="refreshMetrics" 
            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh
        </button>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Monthly Collections -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Monthly Collections</p>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                        AED {{ $metrics['monthly_collections']['formatted_current'] }}
                    </p>
                    <div class="flex items-center mt-1">
                        @if($metrics['monthly_collections']['growth'] >= 0)
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-green-600 dark:text-green-400">+{{ $metrics['monthly_collections']['growth'] }}%</span>
                        @else
                            <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm text-red-600 dark:text-red-400">{{ $metrics['monthly_collections']['growth'] }}%</span>
                        @endif
                    </div>
                </div>
                <div class="p-3 bg-blue-500 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Outstanding Payments -->
        <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Outstanding</p>
                    <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">
                        AED {{ $metrics['outstanding_payments']['formatted'] }}
                    </p>
                    <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                        Expected: AED {{ number_format($metrics['outstanding_payments']['expected'], 0) }}
                    </p>
                </div>
                <div class="p-3 bg-orange-500 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Collection Rate -->
        <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Collection Rate</p>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">
                        {{ $metrics['collection_rate']['rate'] }}%
                    </p>
                    <div class="flex items-center mt-1">
                        <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $metrics['collection_rate']['rate'] }}%"></div>
                        </div>
                        <span class="text-xs text-green-600 dark:text-green-400 capitalize">
                            {{ $metrics['collection_rate']['status'] }}
                        </span>
                    </div>
                </div>
                <div class="p-3 bg-green-500 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Overdue Amount -->
        <div class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">Overdue Amount</p>
                    <p class="text-2xl font-bold text-red-900 dark:text-red-100">
                        AED {{ $metrics['overdue_amounts']['formatted_total'] }}
                    </p>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                        {{ $metrics['overdue_amounts']['count'] }} overdue payments
                    </p>
                </div>
                <div class="p-3 bg-red-500 rounded-full">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Payment Trends Chart -->
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">6-Month Payment Trends</h4>
            <div class="h-48 flex items-end justify-between space-x-2">
                @foreach($chartData['data'] as $index => $amount)
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-t" 
                             style="height: {{ $amount > 0 ? (($amount / max($chartData['data'])) * 160) : 1 }}px;">
                            <div class="w-full bg-blue-500 rounded-t h-full"></div>
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-400 mt-2 transform -rotate-45 origin-left">
                            {{ $chartData['labels'][$index] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Aging Analysis -->
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Overdue Aging Analysis</h4>
            <div class="space-y-3">
                @foreach($agingData['labels'] as $index => $label)
                    @php
                        $amount = $agingData['data'][$index];
                        $total = array_sum($agingData['data']);
                        $percentage = $total > 0 ? ($amount / $total) * 100 : 0;
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <span class="text-sm text-gray-700 dark:text-gray-300 w-20">{{ $label }}</span>
                            <div class="flex-1 mx-3 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full" 
                                     style="width: {{ $percentage }}%; background-color: {{ $agingData['colors'][$index] }}">
                                </div>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                            AED {{ number_format($amount, 0) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tenant Payment Status -->
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Tenant Payment Status</h4>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Paid Tenants -->
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $tenantStatusData['paid'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Paid</div>
                <div class="text-xs text-green-600 dark:text-green-400">{{ $tenantStatusData['paid_percentage'] }}%</div>
            </div>
            
            <!-- Pending Tenants -->
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $tenantStatusData['pending'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Pending</div>
                <div class="text-xs text-yellow-600 dark:text-yellow-400">
                    {{ $tenantStatusData['total'] > 0 ? round(($tenantStatusData['pending'] / $tenantStatusData['total']) * 100, 1) : 0 }}%
                </div>
            </div>
            
            <!-- Overdue Tenants -->
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $tenantStatusData['overdue'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Overdue</div>
                <div class="text-xs text-red-600 dark:text-red-400">
                    {{ $tenantStatusData['total'] > 0 ? round(($tenantStatusData['overdue'] / $tenantStatusData['total']) * 100, 1) : 0 }}%
                </div>
            </div>
            
            <!-- Total Tenants -->
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tenantStatusData['total'] }}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">Total Active</div>
                <div class="text-xs text-gray-600 dark:text-gray-400">Contracts</div>
            </div>
        </div>
    </div>

    <!-- Additional Metrics -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3">
            <div class="text-purple-600 dark:text-purple-400 font-medium">Late Fees Collected</div>
            <div class="text-lg font-bold text-purple-900 dark:text-purple-100">
                AED {{ $metrics['late_fees']['formatted'] }}
            </div>
        </div>
        
        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-3">
            <div class="text-indigo-600 dark:text-indigo-400 font-medium">YoY Growth</div>
            <div class="text-lg font-bold text-indigo-900 dark:text-indigo-100">
                {{ $metrics['growth_metrics']['yoy_growth'] >= 0 ? '+' : '' }}{{ $metrics['growth_metrics']['yoy_growth'] }}%
            </div>
        </div>
        
        <div class="bg-teal-50 dark:bg-teal-900/20 rounded-lg p-3">
            <div class="text-teal-600 dark:text-teal-400 font-medium">Last Updated</div>
            <div class="text-lg font-bold text-teal-900 dark:text-teal-100">
                {{ now()->format('H:i') }}
            </div>
        </div>
    </div>
</div>