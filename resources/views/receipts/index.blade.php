<x-layouts.app :title="__('Receipts Management')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium mb-4">Select a Contract to Create Receipt</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Contract #</th>
                                    <th class="py-3 px-6 text-left">Tenant</th>
                                    <th class="py-3 px-6 text-left">Property</th>
                                    <th class="py-3 px-6 text-left">Start Date</th>
                                    <th class="py-3 px-6 text-left">End Date</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                @forelse($contracts as $contract)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left">{{ $contract->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $contract->tenant->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $contract->property->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-6 text-left">{{ $contract->cstart->format('d M Y') }}</td>
                                    <td class="py-3 px-6 text-left">{{ $contract->cend->format('d M Y') }}</td>
                                    <td class="py-3 px-6 text-left">
                                        <span class="px-2 py-1 rounded-full text-xs
                                            @if($contract->status === 'ACTIVE') bg-green-200 text-green-800
                                            @elseif($contract->status === 'EXPIRED') bg-red-200 text-red-800
                                            @else bg-yellow-200 text-yellow-800 @endif">
                                            {{ $contract->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('receipts.create', $contract->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded text-xs">
                                                Create Receipt
                                            </a>
                                            <a href="{{ route('receipts.list-by-contract', $contract->id) }}" class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded text-xs">
                                                View Receipts
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-3 px-6 text-center">No contracts found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
