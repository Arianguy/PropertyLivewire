<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Select a Contract to Create Receipt</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Contract #</th>
                                    <th class="py-3 px-6 text-left">Tenant</th>
                                    <th class="py-3 px-6 text-left">Property</th>
                                    <th class="py-3 px-6 text-left">Start Date</th>
                                    <th class="py-3 px-6 text-left">End Date</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 dark:text-gray-400 text-sm">
                                @forelse($contracts as $contract)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="py-3 px-6 text-left text-gray-900 dark:text-gray-100">{{ $contract->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $contract->tenant->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $contract->property->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-6 text-left">{{ $contract->cstart->format('d M Y') }}</td>
                                    <td class="py-3 px-6 text-left">{{ $contract->cend->format('d M Y') }}</td>
                                    <td class="py-3 px-6 text-left">
                                        <span @class([
                                            'px-2 py-1 rounded-full text-xs font-medium',
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $contract->status === 'ACTIVE',
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' => $contract->status === 'EXPIRED',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $contract->status !== 'ACTIVE' && $contract->status !== 'EXPIRED',
                                        ])>
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
                                    <td colspan="7" class="py-3 px-6 text-center text-gray-500 dark:text-gray-400">No contracts found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
