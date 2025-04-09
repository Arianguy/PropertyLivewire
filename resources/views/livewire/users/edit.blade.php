<div>
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Edit User</h2>
        <a href="{{ route('users.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-gray-100 dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm hover:bg-gray-200 dark:hover:bg-gray-700">
            <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
            Back to Users
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
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Email</label>
                <div class="mt-2">
                    <input type="email" wire:model="email" id="email" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-sm sm:leading-6">
                    @error('email') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Password (leave blank to keep current)</label>
                <div class="mt-2">
                    <input type="password" wire:model="password" id="password" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-sm sm:leading-6">
                    @error('password') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Confirm Password</label>
                <div class="mt-2">
                    <input type="password" wire:model="password_confirmation" id="password_confirmation" class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:bg-gray-800 sm:text-sm sm:leading-6">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Roles</label>
                <div class="mt-2 space-y-2">
                    @foreach($availableRoles as $role)
                        <div class="flex items-center">
                            <input type="checkbox" wire:model="roles" value="{{ $role->name }}" id="role_{{ $role->id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                            <label for="role_{{ $role->id }}" class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $role->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('roles') <span class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                <flux:icon name="check" class="-ml-0.5 h-5 w-5" />
                Update User
            </button>
        </div>
    </form>
</div>
