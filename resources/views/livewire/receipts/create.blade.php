<div>
    <div class="bg-gray-100 min-h-screen -mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Contract: {{ $contract->name }}</h2>
                        <p class="text-sm text-gray-600">Tenant: {{ $contract->tenant->name }}</p>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('receipts.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            &laquo; Back to Receipts
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm">
                <div class="border-b border-gray-200 p-4">
                    <h3 class="text-lg font-medium text-blue-700">Record Receipt</h3>
                </div>
                @livewire('receipts.form', ['contract' => $contract->id])
            </div>
        </div>
    </div>
</div>
