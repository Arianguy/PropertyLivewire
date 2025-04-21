<div>
    {{-- Header section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Payments</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage payments made for properties.
            </p>
        </div>
        <div class="flex space-x-3">
            @can('create payments')
            <a href="{{ route('payments.create') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-black shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
                Add New Payment
            </a>
            @endcan
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
        {{-- Search --}}
        <div class="md:w-64 relative rounded-md shadow-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Search payments...">
        </div>

        <div class="flex items-center space-x-3">
            {{-- Property Filter --}}
            <div class="w-48">
                <select wire:model.live="propertyIdFilter" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    <option value="">All Properties</option>
                    @foreach($properties as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Payment Type Filter --}}
            <div class="w-48">
                <select wire:model.live="paymentTypeIdFilter" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    <option value="">All Payment Types</option>
                    @foreach($paymentTypes as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Session Message --}}
    @if (session()->has('message'))
        <div class="mt-4 rounded-md bg-green-50 p-4 dark:bg-green-900">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon name="check-circle" class="h-5 w-5 text-green-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Desktop Table --}}
    <div class="mt-6 flow-root hidden sm:block">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                {{-- Sorting Headers --}}
                                <th scope="col" wire:click="sortBy('paid_at')" class="cursor-pointer py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">
                                    Date
                                    @if($sortField === 'paid_at')
                                        <flux:icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="h-4 w-4 inline-block ml-1" />
                                    @else
                                        <flux:icon name="chevron-up-down" class="h-4 w-4 inline-block ml-1 text-gray-400" />
                                    @endif
                                </th>
                                <th scope="col" wire:click="sortBy('properties.name')" class="cursor-pointer px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Property
                                     @if($sortField === 'properties.name')
                                        <flux:icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="h-4 w-4 inline-block ml-1" />
                                    @else
                                        <flux:icon name="chevron-up-down" class="h-4 w-4 inline-block ml-1 text-gray-400" />
                                    @endif
                                </th>
                                <th scope="col" wire:click="sortBy('payment_types.name')" class="cursor-pointer px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Type
                                    @if($sortField === 'payment_types.name')
                                        <flux:icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="h-4 w-4 inline-block ml-1" />
                                    @else
                                        <flux:icon name="chevron-up-down" class="h-4 w-4 inline-block ml-1 text-gray-400" />
                                    @endif
                                </th>
                                <th scope="col" wire:click="sortBy('amount')" class="cursor-pointer px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Amount
                                     @if($sortField === 'amount')
                                        <flux:icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="h-4 w-4 inline-block ml-1" />
                                    @else
                                        <flux:icon name="chevron-up-down" class="h-4 w-4 inline-block ml-1 text-gray-400" />
                                    @endif
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Description</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Contract</th>
                                <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">Attachment</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Recorded By</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                            @forelse($payments as $payment)
                                <tr wire:key="payment-{{ $payment->id }}">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $payment->paid_at->format('M d, Y') }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->property->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->paymentType->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-right">{{ number_format($payment->amount, 2) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($payment->description, 40) }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->contract->contract_identifier ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                        @if($attachment = $payment->getFirstMedia('attachments'))
                                            <a href="{{ $attachment->getUrl() }}" target="_blank" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                                <flux:icon name="paper-clip" class="h-5 w-5 inline-block"/>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->user->name ?? 'N/A' }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <div class="flex justify-end space-x-2">
                                            @can('edit payments')
                                                <a href="{{ route('payments.edit', $payment->id) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400" title="Edit Payment">
                                                    <flux:icon name="pencil-square" class="h-5 w-5" />
                                                    <span class="sr-only">Edit</span>
                                                </a>
                                            @endcan
                                            @can('delete payments')
                                                <button type="button" wire:click="deletePayment({{ $payment->id }})" wire:confirm="Are you sure you want to delete this payment?" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400" title="Delete Payment">
                                                    <flux:icon name="trash" class="h-5 w-5" />
                                                    <span class="sr-only">Delete</span>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                        No payments found.
                                        @can('create payments')
                                        <a href="{{ route('payments.create') }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                            Add your first payment
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Responsive Cards for Mobile --}}
    <div class="mt-4 block sm:hidden">
        <div class="space-y-4">
            @forelse ($payments as $payment)
                <div class="bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                            {{ $payment->paymentType->name ?? 'Payment' }} on {{ $payment->paid_at->format('M d, Y') }}
                        </h3>
                        <div class="flex space-x-2">
                             @can('edit payments')
                                <a href="{{ route('payments.edit', $payment->id) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                    <flux:icon name="pencil-square" class="h-5 w-5" />
                                </a>
                            @endcan
                            @can('delete payments')
                                <button type="button" wire:click="deletePayment({{ $payment->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400">
                                    <flux:icon name="trash" class="h-5 w-5" />
                                </button>
                            @endcan
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <dl>
                            <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Property</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $payment->property->name ?? 'N/A' }}</dd>
                            </div>
                            <div class="bg-white dark:bg-gray-900 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ number_format($payment->amount, 2) }}</dd>
                            </div>
                             <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $payment->description ?? '-' }}</dd>
                            </div>
                            <div class="bg-white dark:bg-gray-900 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contract</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $payment->contract->contract_identifier ?? '-' }}</dd>
                            </div>
                             <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Attachment</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                     @if($attachment = $payment->getFirstMedia('attachments'))
                                        <a href="{{ $attachment->getUrl() }}" target="_blank" class="link link-primary">
                                            View Attachment
                                        </a>
                                    @else
                                        -
                                    @endif
                                </dd>
                            </div>
                            <div class="bg-white dark:bg-gray-900 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Recorded By</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $payment->user->name ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @empty
                 <div class="bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No payments found.</p>
                    @can('create payments')
                    <a href="{{ route('payments.create') }}" class="mt-2 inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                        <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
                        Add Payment
                    </a>
                    @endcan
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
