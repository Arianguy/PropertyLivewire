<x-layouts.app :title="__('Record Receipt for Contract: ') . $contract->name">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Record Receipt for Contract: {{ $contract->name }}</h2>
                <p class="text-gray-600">Tenant: {{ $contract->tenant->name }}</p>
            </div>
            <livewire:receipt-form :contract="$contract->id" />
        </div>
    </div>
</x-layouts.app>
