<div>
    <div class="w-full">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Cheque Status Report</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View cleared and upcoming cheque details.
                </p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-start space-y-3 md:space-y-0 md:space-x-3">
            {{-- Filter Buttons --}}
            <div class="inline-flex rounded-md shadow-sm">
                <button
                    type="button"
                    wire:click="setFilter('cleared')"
                    @class([
                        'relative inline-flex items-center px-3 py-2 text-sm font-semibold focus:z-10',
                        'rounded-l-md',
                        'bg-green-100 text-gray-900 hover:bg-green-200' => $currentFilter === 'cleared',
                        'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700' => $currentFilter !== 'cleared',
                    ])
                >
                    Cleared Cheques
                </button>
                <button
                    type="button"
                    wire:click="setFilter('upcoming')"
                    @class([
                        'relative -ml-px inline-flex items-center px-3 py-2 text-sm font-semibold focus:z-10',
                        'rounded-r-md',
                        'bg-green-100 text-gray-900 hover:bg-green-200' => $currentFilter === 'upcoming',
                        'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700' => $currentFilter !== 'upcoming',
                    ])
                >
                    Upcoming
                </button>
            </div>

            {{-- Search Box --}}
            <div class="md:w-64 relative rounded-md shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <flux:icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Property/Tenant/Contract/Cheque#">
            </div>

            {{-- Date Filters & Action Buttons --}}
            <div class="flex flex-grow items-center justify-between space-x-2 md:justify-end">
                {{-- Always show date filters now --}}
                <div class="flex items-center space-x-2">
                    <input type="date" wire:model.live="startDate" class="block w-auto rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    <span class="text-gray-500">to</span>
                    <input type="date" wire:model.live="endDate" class="block w-auto rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                    <button wire:click="clearDates" type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1.5 rounded focus:outline-none focus:ring-2 focus:ring-primary-500" title="Clear Dates">
                        <flux:icon name="arrow-uturn-left" class="h-4 w-4"/>
                    </button>
                </div>

                {{-- Email and Print Buttons --}}
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

        {{-- Table Section --}}
        <div class="mt-6 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    @foreach ($columns as $column)
                                        <th scope="col" class="{{ $loop->first ? 'py-3.5 pl-4 pr-3 text-left' : 'px-3 py-3.5' }} {{ str_contains(strtolower($column), 'amount') ? 'text-right' : 'text-left' }} text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                @forelse ($reportData as $index => $receipt)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $index + 1 }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $receipt->property_name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $receipt->tenant_name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $receipt->contract_name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $receipt->cheque_no }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $receipt->cheque_bank }}</td>
                                        @if($currentFilter === 'cleared')
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $receipt->deposit_date ? \Carbon\Carbon::parse($receipt->deposit_date)->format('d-M-Y') : 'N/A' }}</td>
                                        @else
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $receipt->cheque_date ? \Carbon\Carbon::parse($receipt->cheque_date)->format('d-M-Y') : 'N/A' }}</td>
                                        @endif
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-right">{{ number_format($receipt->amount, 2) }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            {{-- Calculate diffInDays first to use in @class --}}
                                            @php
                                                $diffInDays = null;
                                                if ($receipt->status === 'PENDING' && $receipt->cheque_date) {
                                                    $chequeDate = \Carbon\Carbon::parse($receipt->cheque_date)->startOfDay();
                                                    $today = \Carbon\Carbon::now()->startOfDay();
                                                    $diffInDays = $today->diffInDays($chequeDate, false); // Negative if past, positive if future
                                                }
                                            @endphp
                                            <span @class([
                                                'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                                // Existing Statuses
                                                'bg-green-50 text-green-700 ring-green-600/20' => $receipt->status === 'CLEARED',
                                                'bg-red-50 text-red-700 ring-red-600/20' => $receipt->status === 'BOUNCED',
                                                // Pending Statuses based on date diff
                                                'bg-red-50 text-red-700 ring-red-600/20' => $receipt->status === 'PENDING' && $diffInDays !== null && $diffInDays < 0, // Late
                                                'bg-yellow-50 text-yellow-800 ring-yellow-600/20' => $receipt->status === 'PENDING' && $diffInDays !== null && $diffInDays === 0, // Due Today
                                                'bg-green-50 text-green-700 ring-green-600/20' => $receipt->status === 'PENDING' && $diffInDays !== null && $diffInDays > 0, // Future
                                                // Fallback for Pending if date is missing or other statuses
                                                'bg-gray-50 text-gray-600 ring-gray-500/10' => $receipt->status === 'PENDING' && $diffInDays === null,
                                                'bg-gray-50 text-gray-600 ring-gray-500/10' => !in_array($receipt->status, ['CLEARED', 'PENDING', 'BOUNCED']),
                                            ])>
                                                @if ($receipt->status === 'PENDING' && $diffInDays !== null)
                                                    @if ($diffInDays > 0)
                                                        Due in {{ $diffInDays }} {{ Str::plural('day', $diffInDays) }}
                                                    @elseif ($diffInDays === 0)
                                                        Due Today
                                                    @else
                                                        Late by {{ abs($diffInDays) }} {{ Str::plural('day', abs($diffInDays)) }}
                                                    @endif
                                                @else
                                                    {{-- Fallback or other statuses --}}
                                                    {{ ucfirst(strtolower($receipt->status)) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                            @if($receipt->hasMedia('cheque_images'))
                                                <button wire:click="viewChequeImage({{ $receipt->id }})" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-600" title="View Cheque Image">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </button>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($columns) }}" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                            No {{ strtolower($currentFilter) }} cheques found matching criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                             @if ($reportData->isNotEmpty())
                                <tfoot class="bg-gray-50 dark:bg-gray-800">
                                    <tr class="font-bold text-base">
                                        <td colspan="7" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Grand Totals ({{ $grandTotals['cheques'] }} Cheques):</td>
                                        <td class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($grandTotals['amount'], 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Optional: Add pagination links if using WithPagination --}}
        {{-- <div class="mt-4">
            {{ $reportData->links() }}
        </div> --}}
    </div>

    {{-- Image View Modal --}}
    <livewire:modals.image-view-modal />

    {{-- Flash Messages --}}
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            class="fixed bottom-5 right-5 bg-red-500 text-white py-2 px-4 rounded-xl text-sm z-50">
            <p>{{ session('error') }}</p>
        </div>
    @endif
</div>
