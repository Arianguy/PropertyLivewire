<div>
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Create Role</h2>
        <a href="{{ route('roles.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-gray-100 dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm hover:bg-gray-200 dark:hover:bg-gray-700">
            <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
            Back to Roles
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
                <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Permissions</label>
                <div class="mt-2 space-y-4">
                    @foreach($availablePermissions as $moduleName => $modulePermissions)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <h3 class="text-base font-medium text-gray-900 dark:text-gray-100">{{ $moduleName }}</h3>

                            <!-- Group permissions by action type (view, create, edit, delete) -->
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <!-- View permissions -->
                                <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">View Access</h4>
                                    <div class="space-y-2">
                                        @foreach($modulePermissions->filter(fn($p) => str_contains($p->name, 'view')) as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}" id="permission_{{ $permission->id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ str_replace('view ', '', $permission->name) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Create permissions -->
                                <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Create Access</h4>
                                    <div class="space-y-2">
                                        @foreach($modulePermissions->filter(fn($p) => str_contains($p->name, 'create')) as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}" id="permission_{{ $permission->id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ str_replace('create ', '', $permission->name) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Edit permissions -->
                                <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Edit Access</h4>
                                    <div class="space-y-2">
                                        @foreach($modulePermissions->filter(fn($p) => str_contains($p->name, 'edit')) as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}" id="permission_{{ $permission->id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ str_replace('edit ', '', $permission->name) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Delete permissions -->
                                <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Delete Access</h4>
                                    <div class="space-y-2">
                                        @foreach($modulePermissions->filter(fn($p) => str_contains($p->name, 'delete')) as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}" id="permission_{{ $permission->id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ str_replace('delete ', '', $permission->name) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if($moduleName === 'Contract Management')
                                <!-- Special Contract permissions -->
                                <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Special Access</h4>
                                    <div class="space-y-2">
                                        @php
                                            $specialPermissions = $modulePermissions->filter(fn($p) => str_contains($p->name, 'renew') || str_contains($p->name, 'terminate'));
                                        @endphp

                                        @foreach($specialPermissions as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox" wire:model="permissions" value="{{ $permission->name }}" id="permission_{{ $permission->id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                                <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($permission->name) }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                <flux:icon name="check" class="-ml-0.5 h-5 w-5" />
                Save
            </button>
        </div>
    </form>
</div>
