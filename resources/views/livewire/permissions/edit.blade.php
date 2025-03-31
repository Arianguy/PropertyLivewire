<div>
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit Permission</h2>
        <a href="{{ route('permissions.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-gray-100 dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm hover:bg-gray-200 dark:hover:bg-gray-700">
            <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
            Back to Permissions
        </a>
    </div>

    <form wire:submit="save" class="mt-8 space-y-6">
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Name</label>
                <div class="mt-2">
                    <input type="text" wire:model="name" id="name" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-sm sm:leading-6">
                    @error('name') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Description</label>
                <div class="mt-2">
                    <input type="text" wire:model="description" id="description" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-sm sm:leading-6">
                    @error('description') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="module_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Module</label>
                <div class="mt-2">
                    <select wire:model="module_id" id="module_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-sm sm:leading-6">
                        <option value="">Select a module</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                    @error('module_id') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="permission_group_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Permission Group</label>
                <div class="mt-2">
                    <select wire:model="permission_group_id" id="permission_group_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-sm sm:leading-6">
                        <option value="">Select a permission group</option>
                        @foreach($permissionGroups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                    @error('permission_group_id') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-red shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                <flux:icon name="check" class="-ml-0.5 h-5 w-5" />
                Update Permission
            </button>
        </div>
    </form>
</div>
