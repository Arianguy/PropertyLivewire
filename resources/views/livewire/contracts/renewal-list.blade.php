<div>
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contracts for Renewal</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    These contracts are active and eligible for renewal.
                </p>
            </div>
            <a href="{{ route('contracts.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                Back to Contracts
            </a>
        </div>

        <div class="mt-4">
            <div class="md:w-64 relative rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Search...">
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
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">End Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Expiry</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Amount</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                @forelse ($validContracts as $contract)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $contract->name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->tenant->name ?? 'N/A' }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->property->name ?? 'N/A' }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($contract->cend)->format('M d, Y') }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            @php
                                                $daysLeft = \Carbon\Carbon::parse($contract->cend)->diffInDays(now());
                                                $expired = \Carbon\Carbon::parse($contract->cend)->isPast();
                                            @endphp

                                            @if($expired)
                                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                    Expired
                                                </span>
                                            @elseif($daysLeft <= 30)
                                                <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                                    {{ $daysLeft }} days left
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                    {{ $daysLeft }} days left
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">${{ number_format($contract->amount, 2) }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('contracts.show', $contract) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                                    <flux:icon name="eye" class="h-5 w-5" />
                                                    <span class="sr-only">View</span>
                                                </a>
                                                <a href="{{ route('contracts.renew', $contract) }}" class="text-green-600 hover:text-green-900 dark:text-green-500 dark:hover:text-green-400">
                                                    <flux:icon name="arrow-path" class="h-5 w-5" />
                                                    <span class="sr-only">Renew</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                            No contracts available for renewal.
                                            <a href="{{ route('contracts.table') }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                                View all contracts
                                            </a>
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
                @forelse ($validContracts as $contract)
                    <div class="bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                Contract #{{ $contract->name }}
                            </h3>
                            <div class="flex space-x-2">
                                <a href="{{ route('contracts.show', $contract) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                    <flux:icon name="eye" class="h-5 w-5" />
                                </a>
                                <a href="{{ route('contracts.renew', $contract) }}" class="text-green-600 hover:text-green-900 dark:text-green-500 dark:hover:text-green-400">
                                    <flux:icon name="arrow-path" class="h-5 w-5" />
                                </a>
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
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">End Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ \Carbon\Carbon::parse($contract->cend)->format('M d, Y') }}</dd>
                                </div>
                                <div class="bg-white dark:bg-gray-900 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Expiry</dt>
                                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                        @php
                                            $daysLeft = \Carbon\Carbon::parse($contract->cend)->diffInDays(now());
                                            $expired = \Carbon\Carbon::parse($contract->cend)->isPast();
                                        @endphp

                                        @if($expired)
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                Expired
                                            </span>
                                        @elseif($daysLeft <= 30)
                                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                                {{ $daysLeft }} days left
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                {{ $daysLeft }} days left
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">${{ number_format($contract->amount, 2) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg p-6 text-center">
                        <p class="text-gray-500 dark:text-gray-400">No contracts available for renewal.</p>
                        <a href="{{ route('contracts.table') }}" class="mt-2 inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                            View all contracts
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-4">
            {{ $validContracts->links() }}
        </div>
    </div>
</div>
