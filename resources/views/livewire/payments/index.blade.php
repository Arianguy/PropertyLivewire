<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <x-card title="Payments" subtitle="Manage your property expenses and payments.">
            {{-- Header with Create Button --}}
            <x-slot name="action">
                @can('create payments')
                    <x-button primary label="Create Payment" href="{{ route('payments.create') }}" />
                @endcan
            </x-slot>

            {{-- Filters --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <x-flux::input
                    wire:model.live.debounce.500ms="search"
                    icon="magnifying-glass"
                    placeholder="Search Amount, Desc, Property..."
                    shadowless
                 />
                <x-select
                    wire:model.live="propertyId"
                    placeholder="Filter by Property"
                    :options="$this->properties"
                    option-label="name"
                    option-value="id"
                    clearable
                />
                <x-select
                    wire:model.live="paymentTypeId"
                    placeholder="Filter by Type"
                    :options="$this->paymentTypes"
                    option-label="name"
                    option-value="id"
                    clearable
                />
            </div>

            {{-- Payment Table --}}
            <div class="mt-6 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        {{-- Sortable Header Function --}}
                                        @php
                                        $headerClasses = 'py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6';
                                        // Pass sortBy and sortDirection as arguments to satisfy linter
                                        $sortableHeader = function (string $field, string $label, string $currentSortBy, string $currentSortDirection) use ($headerClasses) {
                                            $isSorting = $currentSortBy === $field;
                                            $directionIcon = $isSorting ? ($currentSortDirection === 'asc' ? 'chevron-up' : 'chevron-down') : 'chevron-up-down';
                                            return sprintf('<th scope="col" class="%s cursor-pointer" wire:click="sortBy(\'%s\')"> <div class="flex items-center"> <span>%s</span> <x-flux::icon name="%s" class="ml-2 h-4 w-4 %s" /> </div> </th>', $headerClasses, $field, $label, $directionIcon, $isSorting ? '' : 'text-gray-400 dark:text-gray-500');
                                        };
                                        @endphp

                                        {!! $sortableHeader('paid_at', 'Paid At', $sortBy, $sortDirection) !!}
                                        {!! $sortableHeader('property_id', 'Property', $sortBy, $sortDirection) !!}
                                        <th scope="col" class="{{ $headerClasses }}">Contract</th>
                                        {!! $sortableHeader('payment_type_id', 'Type', $sortBy, $sortDirection) !!}
                                        {!! $sortableHeader('description', 'Description', $sortBy, $sortDirection) !!}
                                        {!! $sortableHeader('amount', 'Amount', $sortBy, $sortDirection) !!}
                                        <th scope="col" class="{{ $headerClasses }}">Attachments</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:bg-gray-900 dark:divide-gray-800">
                                    @forelse ($payments as $payment)
                                        <tr wire:key="payment-{{ $payment->id }}">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $payment->paid_at->format('M d, Y') }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->property?->name ?? 'N/A' }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->contract?->contract_number ?? '-' }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->paymentType?->name ?? 'N/A' }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $payment->description }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-right">{{ number_format($payment->amount, 2) }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                @if($payment->getMedia('receipts')->count() > 0)
                                                    <x-flux::badge flat amber>
                                                        <x-flux::icon name="paper-clip" class="w-4 h-4 mr-1"/>
                                                        {{ $payment->getMedia('receipts')->count() }}
                                                    </x-flux::badge>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <div class="flex space-x-1 justify-end">
                                                    @can('edit payments')
                                                        <x-flux::button
                                                            icon-only
                                                            class="rounded-full"
                                                            size="sm"
                                                            icon="pencil"
                                                            primary
                                                            :href="route('payments.edit', $payment)"
                                                            tooltip="Edit Payment"
                                                        />
                                                    @endcan
                                                    @can('delete payments')
                                                        <x-flux::button
                                                            icon-only
                                                            class="rounded-full"
                                                            size="sm"
                                                            icon="trash"
                                                            negative
                                                            wire:click="delete({{ $payment->id }})"
                                                            wire:confirm="Are you sure you want to delete this payment? Associated attachments will also be removed."
                                                            tooltip="Delete Payment"
                                                        />
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                                                <div class="py-8">
                                                     <x-flux::icon name="folder-open" class="w-12 h-12 mx-auto mb-2 text-gray-400" />
                                                    No payments found.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="pt-4">
                {{ $payments->links() }}
            </div>
        </x-card>
    </div>
</div>
