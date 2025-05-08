<div class="w-full">
    {{-- Header Section --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Receipts Management</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Select a contract to manage its receipts.
            </p>
        </div>
        {{-- Intentionally leaving top-right buttons out as per previous reasoning --}}
        {{-- <div class="flex space-x-3">
            <a href="#" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-black shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
                Placeholder Button
            </a>
        </div> --}}
    </div>

    {{-- Search and Filters Section --}}
    <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div class="md:w-64 relative rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Search contracts...">
        </div>
        {{-- Placeholder for potential filters if needed in future --}}
        {{-- <div class="flex items-center space-x-3">
        </div> --}}
    </div>

    {{-- Table Section --}}
    <div class="mt-6 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Contract #</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Tenant</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Property</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Start Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">End Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center">
                                    <span class="sr-only">Actions</span>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse($contracts as $contract)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $contract->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->tenant->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->property->name ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->cstart->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->cend->format('M d, Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    @if($contract->validity === 'YES')
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900 dark:text-green-300">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-900 dark:text-red-300">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('receipts.create', $contract->id) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400" title="Create Receipt">
                                            <flux:icon name="document-plus" class="h-5 w-5" />
                                            <span class="sr-only">Create Receipt</span>
                                        </a>
                                        <a href="{{ route('receipts.list-by-contract', $contract->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-500 dark:hover:text-green-400" title="View Receipts">
                                            <flux:icon name="eye" class="h-5 w-5" />
                                            <span class="sr-only">View Receipts</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                    No active contracts found to manage receipts.
                                    {{-- Optional: Add a link to create contracts if desired --}}
                                    {{-- @if(auth()->user()->can('create contracts'))
                                        <a href="{{ route('contracts.create') }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                            Create a contract first
                                        </a>
                                    @endif --}}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($contracts->hasPages())
        <div class="mt-4">
            {{ $contracts->links() }}
        </div>
    @endif

    {{-- Responsive table for mobile devices (Optional, but good for consistency if contracts page has it) --}}
    {{-- This is a simplified version. You might need to adapt based on contracts/table.blade.php responsive part --}}
    <div class="mt-4 block sm:hidden">
        <div class="space-y-4">
            @forelse ($contracts as $contract)
                <div class="bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            Contract #{{ $contract->name }}
                        </h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('receipts.create', $contract->id) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400" title="Create Receipt">
                                <flux:icon name="document-plus" class="h-5 w-5" />
                            </a>
                            <a href="{{ route('receipts.list-by-contract', $contract->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-500 dark:hover:text-green-400" title="View Receipts">
                                <flux:icon name="eye" class="h-5 w-5" />
                            </a>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200 dark:sm:divide-gray-700">
                            <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $contract->tenant->name }}</dd>
                            </div>
                            <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Property</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $contract->property->name ?? 'N/A' }}</dd>
                            </div>
                            <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Period</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">{{ $contract->cstart->format('M d, Y') }} to {{ $contract->cend->format('M d, Y') }}</dd>
                            </div>
                            <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                    @if($contract->validity === 'YES')
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900 dark:text-green-300">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-900 dark:text-red-300">
                                            Inactive
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No active contracts found to manage receipts.</p>
                </div>
            @endforelse
        </div>
        @if ($contracts->hasPages())
            <div class="mt-4">
                {{ $contracts->links() }}
            </div>
        @endif
    </div>
</div>
