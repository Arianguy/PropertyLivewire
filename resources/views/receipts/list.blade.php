<x-layouts.app :title="__('Receipts for Contract: ') . $contract->number">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Contract Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                            <div>
                                <p class="text-sm text-gray-600">Tenant</p>
                                <p class="font-medium">{{ $contract->tenant->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Property</p>
                                <p class="font-medium">{{ $contract->property->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Contract Period</p>
                                <p class="font-medium">{{ $contract->cstart->format('d M Y') }} - {{ $contract->cend->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-medium mb-4">Receipt History</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Receipt ID</th>
                                    <th class="py-3 px-6 text-left">Date</th>
                                    <th class="py-3 px-6 text-left">Category</th>
                                    <th class="py-3 px-6 text-left">Amount</th>
                                    <th class="py-3 px-6 text-left">Payment Type</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                @forelse($receipts as $receipt)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left">{{ $receipt->id }}</td>
                                    <td class="py-3 px-6 text-left">{{ $receipt->receipt_date->format('d M Y') }}</td>
                                    <td class="py-3 px-6 text-left">{{ $receipt->receipt_category }}</td>
                                    <td class="py-3 px-6 text-left">{{ number_format($receipt->amount, 2) }}</td>
                                    <td class="py-3 px-6 text-left">{{ $receipt->payment_type }}</td>
                                    <td class="py-3 px-6 text-left">
                                        <span class="px-2 py-1 rounded-full text-xs
                                            @if($receipt->status === 'COMPLETED') bg-green-200 text-green-800
                                            @elseif($receipt->status === 'BOUNCED') bg-red-200 text-red-800
                                            @else bg-yellow-200 text-yellow-800 @endif">
                                            {{ $receipt->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">
                                            <a href="#" class="text-blue-500 hover:text-blue-700 mx-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-3 px-6 text-center">No receipts found for this contract</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $receipts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
