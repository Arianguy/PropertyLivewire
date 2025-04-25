<div class="w-full">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contracts</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage your property contracts and their details.
            </p>
        </div>
        <div class="flex space-x-3">
            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('renew contracts'))
            <a href="{{ route('contracts.renewal-list') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                <flux:icon name="arrow-path" class="-ml-0.5 h-5 w-5" />
                Renew Contract
            </a>
            @endif

            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('create contracts'))
            <a href="{{ route('contracts.create') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-black shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
                Create Contract
            </a>
            @endif
        </div>
    </div>

    <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        <div class="md:w-64 relative rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Search...">
        </div>

        <div class="flex items-center space-x-3">
            <!-- Status Filter -->
            <div class="w-48">
                <select wire:model.live="statusFilter" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    <option value="">All Statuses</option>
                    <option value="YES">Active</option>
                    <option value="NO">Inactive</option>
                </select>
            </div>

            <!-- Export Button -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <flux:icon name="arrow-down-tray" class="-ml-0.5 h-5 w-5 text-gray-400" />
                    Export
                    <flux:icon name="chevron-down" class="h-5 w-5 text-gray-400" />
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-36 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                    <div class="py-1" role="none">
                        <button wire:click="export('xlsx')" type="button" class="text-left w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem" tabindex="-1">Excel (.xlsx)</button>
                        <button wire:click="export('pdf')" type="button" class="text-left w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem" tabindex="-1">PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse ($contracts as $contract)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $contract->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->tenant->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->property->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($contract->cstart)->format('M d, Y') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($contract->cend)->format('M d, Y') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ number_format($contract->amount, 2) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @if($contract->validity === 'YES')
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end space-x-2">
                                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view contracts'))
                                            <a href="{{ route('contracts.show', $contract) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400" title="View Contract">
                                                <flux:icon name="eye" class="h-5 w-5" />
                                                <span class="sr-only">View</span>
                                            </a>
                                            @endif

                                            @if($contract->validity === 'YES')
                                                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit contracts'))
                                                    @if(!$contract->renewals()->exists())
                                                        <a href="{{ route('contracts.edit', $contract) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400" title="Edit Contract">
                                                            <flux:icon name="pencil-square" class="h-5 w-5" />
                                                            <span class="sr-only">Edit</span>
                                                        </a>
                                                    @endif
                                                @endif

                                                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('create receipts'))
                                                    <a href="{{ route('receipts.create', $contract) }}" class="text-green-600 hover:text-green-900 dark:text-green-500 dark:hover:text-green-400" title="Create Receipt">
                                                        <flux:icon name="document-plus" class="h-5 w-5" />
                                                        <span class="sr-only">Create Receipt</span>
                                                    </a>
                                                @endif

                                                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('terminate contracts'))
                                                    @if(!$contract->renewals()->exists() && $contract->validity === 'YES')
                                                        <a href="{{ route('contracts.terminate', $contract) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-500 dark:hover:text-yellow-400" title="Terminate Contract">
                                                            <flux:icon name="no-symbol" class="h-5 w-5" />
                                                            <span class="sr-only">Terminate</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif

                                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('delete contracts'))
                                            <button type="button" wire:click="deleteContract({{ $contract->id }})" wire:confirm="Are you sure you want to delete this contract?" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400" title="Delete Contract">
                                                <flux:icon name="trash" class="h-5 w-5" />
                                                <span class="sr-only">Delete</span>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                        No contracts found.
                                        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('create contracts'))
                                        <a href="{{ route('contracts.create') }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                            Create your first contract
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive table for mobile devices -->
    <div class="mt-4 block sm:hidden">
        <div class="space-y-4">
            @forelse ($contracts as $contract)
                <div class="bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            Contract #{{ $contract->name }}
                        </h3>
                        <div class="flex space-x-2">
                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view contracts'))
                            <a href="{{ route('contracts.show', $contract) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                <flux:icon name="eye" class="h-5 w-5" />
                            </a>
                            @endif

                            @if($contract->validity === 'YES')
                                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit contracts'))
                                    @if(!$contract->renewals()->exists())
                                    <a href="{{ route('contracts.edit', $contract) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                        <flux:icon name="pencil-square" class="h-5 w-5" />
                                    </a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('create receipts'))
                                    <a href="{{ route('receipts.create', $contract) }}" class="text-green-600 hover:text-green-900 dark:text-green-500 dark:hover:text-green-400">
                                        <flux:icon name="document-plus" class="h-5 w-5" />
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <dl>
                            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->tenant->name ?? 'N/A' }}</dd>
                            </div>
                            <div class="bg-white dark:bg-gray-900 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Property</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->property->name ?? 'N/A' }}</dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Period</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                    {{ \Carbon\Carbon::parse($contract->cstart)->format('M d, Y') }} to
                                    {{ \Carbon\Carbon::parse($contract->cend)->format('M d, Y') }}
                                </dd>
                            </div>
                            <div class="bg-white dark:bg-gray-900 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ number_format($contract->amount, 2) }}</dd>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                    @if($contract->validity === 'YES')
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
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
                    <p class="text-gray-500 dark:text-gray-400">No contracts found.</p>
                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('create contracts'))
                    <a href="{{ route('contracts.create') }}" class="mt-2 inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                        <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
                        Create Contract
                    </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $contracts->links() }}
    </div>
</div>
