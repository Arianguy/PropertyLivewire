<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <a href="{{ route('dashboard') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>

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
                    <flux:navbar.item icon:trailing="chevron-down">Transactions</flux:navbar.item>
                    <flux:navmenu>
                        <flux:navmenu.item :href="route('contracts.table')" :current="request()->routeIs('contracts.*')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="document-text" class="h-5 w-5" />
                                <span>Contracts</span>
                            </div>
                        </flux:navmenu.item>

                        <flux:navmenu.item :href="route('receipts.index')" :current="request()->routeIs('receipts.*')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="currency-dollar" class="h-5 w-5" />
                                <span>Receipts</span>
                            </div>
                        </flux:navmenu.item>

                        @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view-cheque-management'))
                        <flux:navmenu.item :href="route('cheque-management')" :current="request()->routeIs('cheque-management')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="banknotes" class="h-5 w-5" />
                                <span>Cheque Management</span>
                            </div>
                        </flux:navmenu.item>
                        @endif

                        @can('view payments')
                        <flux:navmenu.item :href="route('payments.index')" :current="request()->routeIs('payments.*')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="credit-card" class="h-5 w-5" />
                                <span>Payments</span>
                            </div>
                        </flux:navmenu.item>
                        @endcan
                    </flux:navmenu>
                </flux:dropdown>
                @endif

                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view reports'))
                    <flux:dropdown>
                    <flux:navbar.item icon:trailing="chevron-down">Reports</flux:navbar.item>
                    <flux:navmenu>
                        <flux:navmenu.item :href="route('contracts.table')" :current="request()->routeIs('contracts.*')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="document-text" class="h-5 w-5" />
                                <span>Tenant Reports</span>
                            </div>
                        </flux:navmenu.item>

                        <flux:navmenu.item :href="route('receipts.index')" :current="request()->routeIs('receipts.*')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="currency-dollar" class="h-5 w-5" />
                                <span>Property Reports</span>
                            </div>
                        </flux:navmenu.item>


                        <flux:navmenu.item :href="route('reports.security-deposits')" :current="request()->routeIs('reports.security-deposits')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="banknotes" class="h-5 w-5" />
                                <span>Security Deposit Reports</span>
                            </div>
                        </flux:navmenu.item>

                        <flux:navmenu.item :href="route('reports.cheque-status')" :current="request()->routeIs('reports.cheque-status')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="banknotes" class="h-5 w-5" />
                                <span>Cheque Reports</span>
                            </div>
                        </flux:navmenu.item>

                        <flux:navmenu.item :href="route('reports.contracts')" :current="request()->routeIs('reports.contracts')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="document-text" class="h-5 w-5" />
                                <span>Contracts Report</span>
                            </div>
                        </flux:navmenu.item>

                        <flux:navmenu.item :href="route('reports.tenant-ledger')" :current="request()->routeIs('reports.tenant-ledger')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="document-text" class="h-5 w-5" />
                                <span>Tenant Ledger Report</span>
                            </div>
                        </flux:navmenu.item>

                        <flux:navmenu.item :href="route('payments.index')" :current="request()->routeIs('payments.*')" wire:navigate>
                            <div class="flex items-center gap-2">
                                <flux:icon name="credit-card" class="h-5 w-5" />
                                <span>Payment Transactions</span>
                            </div>
                        </flux:navmenu.item>

                    </flux:navmenu>
                    </flux:dropdown>
                @endif
            </flux:navbar>

            <flux:spacer />

            @php
                $showConfigMenu = auth()->user()->hasRole('Super Admin') ||
                                 auth()->user()->can('view roles') ||
                                 auth()->user()->can('view permissions') ||
                                 auth()->user()->can('view modules') ||
                                 auth()->user()->can('view permission groups') ||
                                 auth()->user()->can('view users');
            @endphp

            @if($showConfigMenu)
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

            <flux:navbar class="mr-1.5 space-x-0.5 py-0!">
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
                <flux:tooltip :content="__('Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        :label="__('Repository')"
                    />
                </flux:tooltip>
                <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits"
                        target="_blank"
                        label="Documentation"
                    />
                </flux:tooltip>
            </flux:navbar>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="end">
                <flux:profile
                    :name="auth()->user()->name"
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

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>

                @php
                    $showManagementMenu = auth()->user()->hasRole('Super Admin') ||
                                          auth()->user()->can('view properties') ||
                                          auth()->user()->can('view owners') ||
                                          auth()->user()->can('view tenants');
                @endphp

                @if($showManagementMenu)
                <flux:navlist.group :heading="__('Management')" class="grid">
                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view properties'))
                    <flux:navlist.item icon="building-office" :href="route('properties.table')" :current="request()->routeIs('properties.*')" wire:navigate>{{ __('Properties') }}</flux:navlist.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view owners'))
                    <flux:navlist.item icon="users" :href="route('owners.table')" :current="request()->routeIs('owners.*')" wire:navigate>{{ __('Owners') }}</flux:navlist.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view tenants'))
                    <flux:navlist.item icon="user-group" :href="route('tenants.table')" :current="request()->routeIs('tenants.*')" wire:navigate>{{ __('Tenants') }}</flux:navlist.item>
                    @endif
                </flux:navlist.group>
                @endif

                @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view contracts'))
                <flux:navlist.group :heading="__('Transactions')" class="grid">
                    <flux:navlist.item icon="document-text" :href="route('contracts.table')" :current="request()->routeIs('contracts.*')" wire:navigate>{{ __('Contracts') }}</flux:navlist.item>
                    <flux:navlist.item icon="currency-dollar" :href="route('receipts.index')" :current="request()->routeIs('receipts.*')" wire:navigate>{{ __('Receipts') }}</flux:navlist.item>

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view-cheque-management'))
                    <flux:navlist.item icon="banknotes" :href="route('cheque-management')" :current="request()->routeIs('cheque-management')" wire:navigate>{{ __('Cheque Management') }}</flux:navlist.item>
                    @endif

                    @can('view payments')
                    <flux:navlist.item icon="credit-card" :href="route('payments.index')" :current="request()->routeIs('payments.*')" wire:navigate>{{ __('Payments') }}</flux:navlist.item>
                    @endcan
                </flux:navlist.group>
                @endif

                @php
                    $showConfigMenu = auth()->user()->hasRole('Super Admin') ||
                                     auth()->user()->can('view roles') ||
                                     auth()->user()->can('view permissions') ||
                                     auth()->user()->can('view modules') ||
                                     auth()->user()->can('view permission groups') ||
                                     auth()->user()->can('view users');
                @endphp

                @if($showConfigMenu)
                <flux:navlist.group :heading="__('Configuration')" class="grid">
                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view roles'))
                    <flux:navlist.item icon="shield-check" :href="route('roles.table')" :current="request()->routeIs('roles.*')" wire:navigate>{{ __('Roles') }}</flux:navlist.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view permissions'))
                    <flux:navlist.item icon="key" :href="route('permissions.table')" :current="request()->routeIs('permissions.*')" wire:navigate>{{ __('Permissions') }}</flux:navlist.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view modules'))
                    <flux:navlist.item icon="squares-2x2" :href="route('modules.table')" :current="request()->routeIs('modules.*')" wire:navigate>{{ __('Modules') }}</flux:navlist.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view permission groups'))
                    <flux:navlist.item icon="folder" :href="route('permission-groups.table')" :current="request()->routeIs('permission-groups.*')" wire:navigate>{{ __('Permission Groups') }}</flux:navlist.item>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('view users'))
                    <flux:navlist.item icon="users" :href="route('users.table')" :current="request()->routeIs('users.*')" wire:navigate>{{ __('Users') }}</flux:navlist.item>
                    @endif
                </flux:navlist.group>
                @endif
            </flux:navlist>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
