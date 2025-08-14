<div>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Properties</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage your real estate properties and their details.
            </p>
        </div>
        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('create properties'))
        <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
            <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
            Create Property
        </a>
        @endif
    </div>

    <div class="mt-4 flex items-center justify-between">
        <div class="w-64 relative rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Search...">
        </div>

        <div class="flex items-center space-x-2">
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
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Type</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Community</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Owner</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse ($properties as $property)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $property->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $property->type }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $property->community }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $property->owner->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                                        @if($property->status === 'VACANT')
                                            <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                VACANT
                                            </span>
                                        @elseif($property->status === 'LEASED')
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                LEASED
                                            </span>
                                        @elseif($property->status === 'MAINTENANCE')
                                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                                MAINTENANCE
                                            </span>
                                        @elseif($property->status === 'SOLD')
                                            <span class="inline-flex items-center rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20">
                                                SOLD
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">
                                                {{ $property->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end gap-2">
                                            <!-- View Property -->
                                            <a href="{{ route('properties.show', $property) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200" title="View Property">
                                                <flux:icon name="eye" class="h-5 w-5" />
                                                <span class="sr-only">View</span>
                                            </a>

                                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit properties'))
                                            <a href="{{ route('properties.edit', $property) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400" title="Edit Property">
                                                <flux:icon name="pencil-square" class="h-5 w-5" />
                                                <span class="sr-only">Edit</span>
                                            </a>
                                            @endif

                                            <!-- Sell Property Button (only for non-sold properties) -->
                                            @if($property->status !== 'SOLD' && !$property->is_archived && (auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit properties')))
                                            <a href="{{ route('properties.sell', $property) }}" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400" title="Sell Property">
                                                <flux:icon name="currency-dollar" class="h-5 w-5" />
                                                <span class="sr-only">Sell</span>
                                            </a>
                                            @endif

                                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('delete properties'))
                                            <button type="button" wire:click="deleteProperty({{ $property->id }})" wire:confirm="Are you sure you want to delete this property?" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400" title="Delete Property">
                                                <flux:icon name="trash" class="h-5 w-5" />
                                                <span class="sr-only">Delete</span>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                        No properties found.
                                        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('create properties'))
                                        <a href="{{ route('properties.create') }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                            Create your first property
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

    <div class="mt-4">
        {{ $properties->links() }}
    </div>
</div>
