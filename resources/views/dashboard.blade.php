<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <!-- Rental Collection Metrics - Enhanced First Box -->
            <div class="md:col-span-2">
                @livewire('dashboard.rental-collection-metrics')
            </div>
            
            <!-- Property Overview - Second Box -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                @livewire('dashboard.property-overview')
            </div>
        </div>
        
        <!-- Recent Activity and Quick Actions -->
        <div class="grid gap-4 md:grid-cols-2">
            <!-- Recent Activity -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                @livewire('dashboard.recent-activity')
            </div>
            
            <!-- Quick Actions -->
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                @livewire('dashboard.quick-actions')
            </div>
        </div>
    </div>
</x-layouts.app>
