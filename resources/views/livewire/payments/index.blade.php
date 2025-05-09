<div class="w-full">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Payments</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your property expenses and payments.</p>
        </div>
        <div class="flex space-x-3">
            @can('create payments')
                <a href="{{ route('payments.create') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-black shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                    <flux:icon name="plus" class="-ml-0.5 h-5 w-5" />
                    Create Payment
                </a>
            @endcan
        </div>
    </div>

    {{-- Filters --}}
    <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0 md:space-x-3">
        {{-- Left Aligned Filters Group --}}
        <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-3">
            {{-- Search --}}
            <div class="md:w-64 relative rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
                </div>
                <input type="text" wire:model.live.debounce.500ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Search Amount, Desc, Property...">
            </div>

            {{-- Property Filter --}}
            <div class="w-full md:w-48">
                <select wire:model.live="propertyId" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    <option value="">All Properties</option>
                    @foreach($this->properties as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Payment Type Filter --}}
            <div class="w-full md:w-48">
                <select wire:model.live="paymentTypeId" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    <option value="">All Types</option>
                    @foreach($this->paymentTypes as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Right Aligned Date Filters & Action Buttons Container --}}
        <div class="flex items-center space-x-2 justify-end">
            {{-- Date Range Filter for Paid At --}}
            <div class="flex items-center space-x-2">
                <label for="startDate" class="text-sm font-medium text-gray-700 dark:text-gray-300">Paid:</label>
                <input type="date" wire:model.live="startDate" id="startDate" class="block w-auto rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:[color-scheme:dark]">
                <span class="text-gray-500 dark:text-gray-400">to</span>
                <input type="date" wire:model.live="endDate" id="endDate" class="block w-auto rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:[color-scheme:dark]">
                <button wire:click="clearDates" type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1.5 rounded focus:outline-none focus:ring-2 focus:ring-primary-500" title="Clear Dates">
                    <flux:icon name="arrow-uturn-left" class="h-4 w-4"/>
                </button>
            </div>

            {{-- Action Buttons: Email and Print --}}
            <div class="flex items-center space-x-2">
                <button wire:click="emailReport" type="button" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500 p-1.5 rounded focus:outline-none focus:ring-2 focus:ring-primary-500" title="Email Report">
                    <span wire:loading wire:target="emailReport" class="animate-spin inline-block h-5 w-5"><flux:icon name="arrow-path"/></span>
                    <span wire:loading.remove wire:target="emailReport"><flux:icon name="envelope" class="h-5 w-5"/></span>
                </button>
                <button wire:click="exportPdf" type="button" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500 p-1.5 rounded focus:outline-none focus:ring-2 focus:ring-primary-500" title="Print/Download PDF Report">
                     <span wire:loading wire:target="exportPdf" class="animate-spin inline-block h-5 w-5"><flux:icon name="arrow-path"/></span>
                    <span wire:loading.remove wire:target="exportPdf"><flux:icon name="printer" class="h-5 w-5"/></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Payment Table (Keep existing inner structure for now) --}}
    <div class="mt-6 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                {{-- Sortable Header Function (Keep for now, styling might need adjustment later) --}}
                                @php
                                $headerClasses = 'py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6';
                                // Pass sortBy and sortDirection as arguments to satisfy linter
                                $sortableHeader = function (string $field, string $label, string $currentSortBy, string $currentSortDirection) use ($headerClasses) {
                                    $isSorting = $currentSortBy === $field;
                                    $directionIcon = $isSorting ? ($currentSortDirection === 'asc' ? 'chevron-up' : 'chevron-down') : 'chevron-up-down';
                                    return sprintf('<th scope="col" class="%s cursor-pointer" wire:click="sortBy(\'%s\')"> <div class="flex items-center"> <span>%s</span> <flux:icon name="%s" class="ml-2 h-4 w-4 %s" /> </div> </th>', $headerClasses, $field, $label, $directionIcon, $isSorting ? '' : 'text-gray-400 dark:text-gray-500');
                                };
                                @endphp

                                {!! $sortableHeader('paid_at', 'Paid At', $sortBy, $sortDirection) !!}
                                {!! $sortableHeader('property_id', 'Property', $sortBy, $sortDirection) !!}
                                <th scope="col" class="{{ $headerClasses }}">Contract</th> {{-- Non-sortable --}}
                                {!! $sortableHeader('payment_type_id', 'Type', $sortBy, $sortDirection) !!}
                                {{-- Amount Header (Manual Definition for Alignment - Now Left) --}}
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer" wire:click="sortBy('amount')">
                                    <div class="flex items-center"> {{-- Removed justify-end --}}
                                        <span>Amount</span>
                                        @php
                                            $isSortingAmount = $sortBy === 'amount';
                                            $amountDirectionIcon = $isSortingAmount ? ($sortDirection === 'asc' ? 'chevron-up' : 'chevron-down') : 'chevron-up-down';
                                        @endphp
                                        <flux:icon name="{{ $amountDirectionIcon }}" class="ml-2 h-4 w-4 {{ $isSortingAmount ? '' : 'text-gray-400 dark:text-gray-500' }}" />
                                    </div>
                                </th>
                                {{-- Description Header (Moved) --}}
                                {!! $sortableHeader('description', 'Description', $sortBy, $sortDirection) !!}
                                {{-- Removed Attachments Header --}}
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
                                    {{-- Amount Cell (Matches Manual Header Padding - Now Left) --}}
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-left">{{ number_format($payment->amount, 2) }}</td>
                                    {{-- Description Cell (Moved, Wrapping Enabled with break-words) --}}
                                    <td class="px-3 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs break-words">{{ $payment->description }}</td>
                                    {{-- Removed Attachments Cell --}}
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        {{-- Actions using standard links/buttons + flux icons --}}
                                        <div class="flex space-x-2 justify-end items-center">
                                            {{-- Attachment Button (New) --}}
                                            @if($payment->getMedia('receipts')->count() > 0)
                                                @php $attachmentCount = $payment->getMedia('receipts')->count(); @endphp
                                                <button type="button" wire:click="showAttachments({{ $payment->id }})" class="text-blue-600 hover:text-blue-900 dark:text-blue-500 dark:hover:text-blue-400 inline-flex items-center space-x-1 text-xs" title="View Attachments ({{ $attachmentCount }})">
                                                    <flux:icon name="paper-clip" class="h-5 w-5" />
                                                    <span>({{ $attachmentCount }})</span> {{-- Show count --}}
                                                </button>
                                            @endif
                                            @can('edit payments')
                                                {{-- Edit: Link + Icon --}}
                                                <a href="{{ route('payments.edit', $payment) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400" title="Edit Payment">
                                                     <flux:icon name="pencil-square" class="h-5 w-5" />
                                                </a>
                                            @endcan
                                            @can('delete payments')
                                                 {{-- Delete: Button + Icon --}}
                                                 <button type="button" wire:click="delete({{ $payment->id }})" wire:confirm="Are you sure you want to delete this payment? Associated attachments will also be removed." class="text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400" title="Delete Payment">
                                                     <flux:icon name="trash" class="h-5 w-5" />
                                                 </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Simplified empty state --}}
                                    <td colspan="8" class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                        No payments found.
                                        @can('create payments')
                                            <a href="{{ route('payments.create') }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400"> Create your first payment</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($payments->hasPages() || (isset($grandTotalAmount) && $totalPaymentsCount > 0) )
                            <tfoot class="bg-gray-50 dark:bg-gray-800">
                                @if (isset($grandTotalAmount) && $totalPaymentsCount > 0)
                                <tr class="font-semibold text-gray-900 dark:text-gray-100">
                                    <td colspan="4" class="py-3.5 pl-4 pr-3 text-left text-sm sm:pl-6">Grand Totals ({{ $totalPaymentsCount }} Payments):</td>
                                    <td class="px-3 py-3.5 text-left text-sm">{{ number_format($grandTotalAmount, 2) }}</td>
                                    <td colspan="2" class="px-3 py-3.5 text-left text-sm"></td> {{-- Adjusted colspan for remaining columns --}}
                                </tr>
                                @endif
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="pt-4">
        {{ $payments->links() }}
    </div>

    {{-- Include the attachments modal --}}
    @livewire('payments.view-attachments')
</div>
