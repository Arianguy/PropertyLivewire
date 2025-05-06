<div>
    <div class="w-full">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contracts Report</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View ongoing and closed contract details.
                </p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-start space-y-3 md:space-y-0 md:space-x-3">
            {{-- Filter Buttons (Ongoing / Closed) --}}
            <div class="inline-flex rounded-md shadow-sm">
                <button
                    type="button"
                    wire:click="setFilter('ongoing')"
                    @class([
                        'relative inline-flex items-center px-3 py-2 text-sm font-semibold focus:z-10',
                        'rounded-l-md',
                        'bg-green-100 text-gray-900 hover:bg-green-200' => $currentFilter === 'ongoing', // Use a different color for ongoing
                        'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700' => $currentFilter !== 'ongoing',
                    ])
                >
                    Ongoing Contracts
                </button>
                <button
                    type="button"
                    wire:click="setFilter('closed')"
                    @class([
                        'relative -ml-px inline-flex items-center px-3 py-2 text-sm font-semibold focus:z-10',
                        'rounded-r-md',
                        'bg-green-100 text-gray-900 hover:bg-green-200' => $currentFilter === 'closed', // Use gray for closed
                        'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700' => $currentFilter !== 'closed',
                    ])
                >
                    Closed Contracts
                </button>
            </div>

             {{-- Search Input --}}
            <div class="flex-grow">
                <label for="search" class="sr-only">Search Contracts</label>
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    id="search"
                    placeholder="Search by Property, Tenant, Contract #..."
                    type="search"
                />
            </div>

            {{-- Date Filters & Action Buttons --}}
            <div class="flex flex-grow items-center justify-between space-x-2 md:justify-end">
                 {{-- Date Filters --}}
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
                                    @if (!empty($columns))
                                        @foreach ($columns as $column)
                                            <th scope="col" class="{{ $loop->first ? 'py-3.5 pl-4 pr-3 text-left' : 'px-3 py-3.5' }} {{ str_contains(strtolower($column), 'amount') ? 'text-right' : 'text-left' }} text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">{{ $column }}</th>
                                        @endforeach
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center text-sm font-semibold text-gray-900 dark:text-gray-100">View</th>
                                    @else
                                         {{-- Fallback if columns are not set --}}
                                         <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Data</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                @if (!empty($reportData))
                                    @forelse ($reportData as $index => $contract)
                                        <tr>
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $index + 1 }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->property_name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->tenant_name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->name ?? 'N/A' }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->cstart ? \Carbon\Carbon::parse($contract->cstart)->format('d-M-Y') : 'N/A' }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $contract->cend ? \Carbon\Carbon::parse($contract->cend)->format('d-M-Y') : 'N/A' }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400 text-right">{{ number_format($contract->amount ?? 0, 2) }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                @php
                                                    $isOngoing = $currentFilter === 'ongoing';
                                                    $endDate = $contract->cend ? \Carbon\Carbon::parse($contract->cend)->startOfDay() : null;
                                                    $today = \Carbon\Carbon::now()->startOfDay();
                                                    $isClosed = $endDate ? $endDate->isPast() : false; // Determine if closed based on cend
                                                    $statusText = $isClosed ? 'Closed' : 'Ongoing'; // Simple status based on cend
                                                    // If you have a specific 'status' column you want to display, use $contract->status here instead.
                                                @endphp
                                                <span @class([
                                                    'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                                    'bg-blue-50 text-blue-700 ring-blue-600/20' => !$isClosed, // Ongoing
                                                    'bg-gray-50 text-gray-600 ring-gray-500/10' => $isClosed, // Closed
                                                ])>
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            {{-- Conditional column based on filter --}}
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                @if ($isOngoing && $endDate && !$isClosed)
                                                    @php $diffInDays = $today->diffInDays($endDate, false); @endphp
                                                    <span @class([
                                                        'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                                        'bg-red-50 text-red-700 ring-red-600/20' => $diffInDays < 0, // Late
                                                        'bg-yellow-50 text-yellow-800 ring-yellow-600/20' => $diffInDays === 0, // Due Today
                                                        'bg-green-50 text-green-700 ring-green-600/20' => $diffInDays > 0, // Upcoming
                                                    ])>
                                                        @if ($diffInDays > 0)
                                                            {{ $diffInDays }} {{ Str::plural('day', $diffInDays) }} remaining
                                                        @elseif ($diffInDays === 0)
                                                            Due Today
                                                        @else
                                                            Late by {{ abs($diffInDays) }} {{ Str::plural('day', abs($diffInDays)) }}
                                                        @endif
                                                    </span>
                                                @elseif (!$isOngoing && $endDate)
                                                    {{ $endDate->format('d-M-Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            {{-- View Action Cell --}}
                                            <td class="whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6">
                                                @php
                                                    // Attempt to get download URL. Use getFirstMediaUrl as a fallback if needed.
                                                    // Ensure the Contract model has getContractCopyDownloadUrl() or adjust.
                                                    $downloadUrl = method_exists($contract, 'getContractCopyDownloadUrl')
                                                                    ? $contract->getContractCopyDownloadUrl()
                                                                    : ($contract->hasMedia('contracts_copy') ? $contract->getFirstMediaUrl('contracts_copy') : null);
                                                @endphp
                                                @if($downloadUrl)
                                                    <a href="{{ $downloadUrl }}"
                                                       target="_blank" {{-- Open in new tab, browser might force download --}}
                                                       {{-- download --}} {{-- Uncomment the download attribute to suggest download --}}
                                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                       title="Download Contract PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>
                                                        {{-- Use a download icon instead if preferred --}}
                                                        {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 inline-block">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                        </svg> --}}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ !empty($columns) ? count($columns) + 1 : 1 }}" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                                No {{ strtolower($currentFilter) }} contracts found matching criteria.
                                            </td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="{{ !empty($columns) ? count($columns) : 1 }}" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6 text-center">
                                            Loading report data...
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                             @if (!empty($reportData) && $reportData->isNotEmpty())
                                <tfoot class="bg-gray-50 dark:bg-gray-800">
                                    <tr class="font-bold text-base">
                                        <td colspan="6" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Grand Totals ({{ $grandTotals['contracts'] }} Contracts):</td>
                                        <td class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($grandTotals['amount'] ?? 0, 2) }}</td>
                                        <td colspan="{{ $currentFilter === 'ongoing' ? 3 : 2 }}"></td>
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

    {{-- Modal for viewing PDF (Removed) --}}

</div>
