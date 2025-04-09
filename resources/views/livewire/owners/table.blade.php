<div>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Owners</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage property owners and their details.
            </p>
        </div>
        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('create owners'))
        <a href="{{ route('owners.create') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
            <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
            Create Owner
        </a>
        @endif
    </div>

    <div class="mt-4">
        <div class="relative rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <flux:icon name="magnifying-glass" class="h-5 w-5 text-gray-400" />
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Search owners...">
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">EID</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Nationality</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Email</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Mobile</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @foreach($owners as $owner)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $owner->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $owner->eid }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $owner->nationality }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $owner->email }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $owner->mobile }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end gap-2">
                                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit owners'))
                                            <a href="{{ route('owners.edit', $owner) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-gray-200 dark:bg-gray-700 text-primary-600 dark:text-primary-400 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                <flux:icon name="pencil-square" class="h-5 w-5" />
                                                <span class="sr-only">Edit</span>
                                            </a>
                                            @endif

                                            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('delete owners'))
                                            <button wire:click="deleteOwner({{ $owner->id }})" wire:confirm="Are you sure you want to delete this owner?" class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-gray-200 dark:bg-gray-700 text-red-600 dark:text-red-400 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                <flux:icon name="trash" class="h-5 w-5" />
                                                <span class="sr-only">Delete</span>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $owners->links() }}
    </div>
</div>
