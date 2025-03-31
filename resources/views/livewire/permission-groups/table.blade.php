<div>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Permission Groups</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Permission groups organize permissions within modules into logical categories.
            </p>
        </div>
        <a href="{{ route('permission-groups.create') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-red shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
            <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
            Create Permission Group
        </a>
    </div>

    <div class="mt-6">
        <div class="rounded-lg bg-blue-50 dark:bg-blue-900/20 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon name="information-circle" class="h-5 w-5 text-blue-400 dark:text-blue-500" />
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-400">About Permission Groups</h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>Permission groups provide a second level of organization within modules. They allow you to:</p>
                        <ul class="list-disc list-inside mt-1 ml-2 space-y-1">
                            <li>Categorize related permissions (e.g., "User Access" vs "User Management")</li>
                            <li>Separate basic view permissions from advanced management permissions</li>
                            <li>Create a clearer hierarchical structure for your permissions</li>
                            <li>Improve the user experience when assigning permissions to roles</li>
                        </ul>
                    </div>
                </div>
            </div>
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
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Description</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Module</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Order</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Permissions</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @foreach($permissionGroups as $permissionGroup)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $permissionGroup->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $permissionGroup->description }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $permissionGroup->module->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $permissionGroup->order }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $permissionGroup->permissions->count() }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('permission-groups.edit', $permissionGroup) }}" class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-gray-200 dark:bg-gray-700 text-primary-600 dark:text-primary-400 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                <flux:icon name="pencil-square" class="h-5 w-5" />
                                                <span class="sr-only">Edit</span>
                                            </a>
                                            <button wire:click="delete({{ $permissionGroup->id }})" wire:confirm="Are you sure you want to delete this permission group?" class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-gray-200 dark:bg-gray-700 text-red-600 dark:text-red-400 hover:bg-gray-300 dark:hover:bg-gray-600">
                                                <flux:icon name="trash" class="h-5 w-5" />
                                                <span class="sr-only">Delete</span>
                                            </button>
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
        {{ $permissionGroups->links() }}
    </div>
</div>
