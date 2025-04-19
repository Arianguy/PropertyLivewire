<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            @php
                $showManagementMenu = auth()->user()->hasRole('Super Admin') ||
                                      auth()->user()->can('view properties') ||
                                      auth()->user()->can('view owners') ||
                                      auth()->user()->can('view tenants');
            @endphp

            @if($showManagementMenu)
            <flux:dropdown>
                <flux:navbar.item icon:trailing="chevron-down">Management</flux:navbar.item>
                <flux:navmenu>
                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view properties'))
                    <flux:navmenu.item :href="route('properties.table')" :current="request()->routeIs('properties.*')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="building-office" class="h-5 w-5" />
                            <span>Properties</span>
                        </div>
                    </flux:navmenu.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view owners'))
                    <flux:navmenu.item :href="route('owners.table')" :current="request()->routeIs('owners.*')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="users" class="h-5 w-5" />
                            <span>Owners</span>
                        </div>
                    </flux:navmenu.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view tenants'))
                    <flux:navmenu.item :href="route('tenants.table')" :current="request()->routeIs('tenants.*')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="user-group" class="h-5 w-5" />
                            <span>Tenants</span>
                        </div>
                    </flux:navmenu.item>
                    @endif
                </flux:navmenu>
            </flux:dropdown>
            @endif

            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view contracts'))
            <flux:dropdown>
                <flux:navbar.item icon:trailing="chevron-down">Contracts</flux:navbar.item>
                <flux:navmenu>
                    <flux:navmenu.item :href="route('contracts.table')" :current="request()->routeIs('contracts.*')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="document-text" class="h-5 w-5" />
                            <span>Contracts</span>
                        </div>
                    </flux:navmenu.item>

                    <flux:navmenu.item :href="route('contracts.renewal-list')" :current="request()->routeIs('contracts.renewal-list')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="arrow-path" class="h-5 w-5" />
                            <span>Contract Renewals</span>
                        </div>
                    </flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>
            @endif

            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view-cheque-management'))
            <flux:dropdown>
                <flux:navbar.item icon:trailing="chevron-down">Transactions</flux:navbar.item>
                <flux:navmenu>
                    <flux:navmenu.item :href="route('cheque-management')" :current="request()->routeIs('cheque-management')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="banknotes" class="h-5 w-5" />
                            <span>Cheque Management</span>
                        </div>
                    </flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>
            @endif

            @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view roles'))
            <flux:dropdown>
                <flux:navbar.item icon:trailing="chevron-down">Config</flux:navbar.item>
                <flux:navmenu>
                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view roles'))
                    <flux:navmenu.item :href="route('roles.table')" :current="request()->routeIs('roles.table')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="shield-check" class="h-5 w-5" />
                            <span>Roles</span>
                        </div>
                    </flux:navmenu.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view permissions'))
                    <flux:navmenu.item :href="route('permissions.table')" :current="request()->routeIs('permissions.table')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="key" class="h-5 w-5" />
                            <span>Permissions</span>
                        </div>
                    </flux:navmenu.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view modules'))
                    <flux:navmenu.item :href="route('modules.table')" :current="request()->routeIs('modules.table')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="squares-2x2" class="h-5 w-5" />
                            <span>Modules</span>
                        </div>
                    </flux:navmenu.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view permission groups'))
                    <flux:navmenu.item :href="route('permission-groups.table')" :current="request()->routeIs('permission-groups.table')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="folder" class="h-5 w-5" />
                            <span>Permission Groups</span>
                        </div>
                    </flux:navmenu.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view users'))
                    <flux:navmenu.item :href="route('users.table')" :current="request()->routeIs('users.table')" wire:navigate>
                        <div class="flex items-center gap-2">
                            <flux:icon name="users" class="h-5 w-5" />
                            <span>Users</span>
                        </div>
                    </flux:navmenu.item>
                    @endif
                </flux:navmenu>
            </flux:dropdown>
            @endif

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
