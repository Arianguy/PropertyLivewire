<div>
    <div class="w-full p-4 md:p-6">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Tenant Ledger Report</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Detailed ledger for each tenant and contract.
                </p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-start space-y-3 md:space-y-0 md:space-x-3">
            {{-- Filter Buttons --}}
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <button
                    type="button"
                    wire:click="setContractFilter('all')"
                    @class([
                        'relative inline-flex items-center px-4 py-2 text-sm font-medium focus:z-10',
                        'rounded-l-lg',
                        'bg-green-100 text-gray-900 hover:bg-green-200' => $currentContractFilter === 'all',
                        'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700' => $currentContractFilter !== 'all',
                    ])
                >
                    All Contracts
                </button>
                <button
                    type="button"
                    wire:click="setContractFilter('active')"
                    @class([
                        'relative inline-flex items-center px-4 py-2 text-sm font-medium focus:z-10',
                        '-ml-px',
                        'bg-green-100 text-gray-900 hover:bg-green-200' => $currentContractFilter === 'active',
                        'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700' => $currentContractFilter !== 'active',
                    ])
                >
                    Active Contracts
                </button>
                <button
                    type="button"
                    wire:click="setContractFilter('inactive')"
                    @class([
                        'relative inline-flex items-center px-4 py-2 text-sm font-medium focus:z-10',
                        'rounded-r-lg',
                        '-ml-px',
                        'bg-green-100 text-gray-900 hover:bg-green-200' => $currentContractFilter === 'inactive',
                        'bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700' => $currentContractFilter !== 'inactive',
                    ])
                >
                    Inactive Contracts
                </button>
            </div>

            {{-- Search Input --}}
            <div class="md:w-80"> {{-- Made search box smaller --}}
                <label for="tenantLedgerSearch" class="sr-only">Search Report</label>
                <flux:input
                    wire:model.live.debounce.300ms="searchTerm"
                    id="tenantLedgerSearch"
                    placeholder="Search by Tenant, Contract ID/Name..."
                    type="search"
                />
            </div>

            {{-- Date Filters --}}
            <div class="flex items-center space-x-2 md:ml-auto"> {{-- Align to the right --}}
                <input type="date" wire:model.live="startDate" class="block w-auto rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                <span class="text-gray-500">to</span>
                <input type="date" wire:model.live="endDate" class="block w-auto rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                <button wire:click="clearDateFilters" type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-1.5 rounded focus:outline-none focus:ring-2 focus:ring-primary-500" title="Clear Dates">
                    <flux:icon name="arrow-uturn-left" class="h-4 w-4"/>
                </button>
            </div>

            {{-- Action Buttons (Email/Print) --}}
            <div class="flex items-center space-x-2 ml-2"> {{-- Added ml-2 for spacing --}}
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

        @if ($contracts && $contracts->count() > 0)
            @foreach ($contracts as $contract)
                <div class="mb-8 bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 ring-1 ring-gray-300 dark:ring-gray-700">
                    {{-- Contract Details Bar - Adjusted text color for better contrast --}}
                    <div class="mb-4 p-3 bg-primary-500 rounded-md flex flex-wrap justify-between items-center text-sm text-gray-800 dark:text-gray-100">
                        <span class="mr-2"><strong class="font-semibold">Contract Name:</strong> {{ $contract->name ?? 'N/A' }}</span>
                        <span class="mr-2"><strong class="font-semibold">Tenant:</strong> {{ $contract->tenant->name ?? 'N/A' }}</span>
                        <span class="mr-2"><strong class="font-semibold">Rent:</strong> {{ number_format($contract->amount, 0) }}</span>
                        <span class="mr-2"><strong class="font-semibold">Start:</strong> {{ $contract->cstart ? $contract->cstart->format('d-M-Y') : 'N/A' }}</span>
                        <span class="mr-2"><strong class="font-semibold">End:</strong> {{ $contract->cend ? $contract->cend->format('d-M-Y') : 'N/A' }}</span>
                        <span>
                            <strong class="font-semibold">Status:</strong>
                            @if ($contract->validity === 'YES')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-200 text-green-800 dark:bg-green-700 dark:text-green-100">ACTIVE</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-100">{{ strtoupper($contract->validity ?? 'CLOSED') }}</span>
                            @endif
                        </span>
                    </div>

                    {{-- Receipts Table --}}
                    <h3 class="text-md font-medium text-gray-700 dark:text-gray-200 mb-3">Receipt History</h3>
                    <div class="flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-900">
                                            <tr>
                                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Trans ID</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Purpose</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Type</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Narration</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Ch Status</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Ch No</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Bank</th>
                                                <th scope="col" class="px-2 py-3.5 text-right text-xs font-semibold text-gray-900 dark:text-gray-100">Amount</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Chq Date</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Deposit On</th>
                                                <th scope="col" class="px-2 py-3.5 text-left text-xs font-semibold text-gray-900 dark:text-gray-100">Deposit AC</th>
                                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-center text-xs font-semibold text-gray-900 dark:text-gray-100">View</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-850">
                                            @forelse ($contract->receipts as $receipt)
                                                <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-800' : '' }}">
                                                    <td class="whitespace-nowrap py-3 pl-4 pr-3 text-xs text-gray-700 dark:text-gray-300 sm:pl-6">{{ $receipt->id }}</td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $receipt->receipt_category }}</td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $receipt->payment_type }}</td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $receipt->narration }}</td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400">
                                                        <span @class([
                                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                            'bg-yellow-100 text-yellow-800' => $receipt->status === 'PENDING',
                                                            'bg-green-100 text-green-800' => $receipt->status === 'CLEARED',
                                                            'bg-red-100 text-red-800' => $receipt->status === 'BOUNCED',
                                                            'bg-blue-100 text-blue-800' => $receipt->status === 'CANCELLED',
                                                            'bg-gray-100 text-gray-800' => !in_array($receipt->status, ['PENDING', 'CLEARED', 'BOUNCED', 'CANCELLED'])
                                                        ])>
                                                            {{ $receipt->status ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $receipt->cheque_no ?? 'N/A' }}</td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $receipt->cheque_bank ?? 'N/A' }}</td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400 text-right">{{ number_format($receipt->amount, 0) }}</td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400">
                                                        @if ($receipt->payment_type === 'CHEQUE' && $receipt->cheque_date)
                                                            {{ $receipt->cheque_date->format('d-M-Y') }}
                                                        @elseif ($receipt->receipt_date)
                                                            {{ $receipt->receipt_date->format('d-M-Y') }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $receipt->deposit_date ? $receipt->deposit_date->format('d-M-Y') : 'No Date Available' }}</td>
                                                    <td class="whitespace-nowrap px-2 py-3 text-xs text-gray-500 dark:text-gray-400">{{ $receipt->deposit_account ?? 'N/A' }}</td>
                                                    <td class="whitespace-nowrap py-3 pl-3 pr-4 text-center text-xs sm:pr-6">
                                                        @if ($receipt->hasAttachment())
                                                            <button
                                                               type="button"
                                                               wire:click="openAttachmentModal('{{ $receipt->getAttachmentUrl() }}')"
                                                               class="inline-flex items-center px-2.5 py-1.5 border border-primary-500 dark:border-primary-400 text-xs font-medium rounded shadow-sm text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                                               title="View Attachment">
                                                                View
                                                            </button>
                                                        @else
                                                            <span class="text-xs text-gray-400 dark:text-gray-500">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-center text-gray-500 dark:text-gray-400 sm:pl-6">
                                                        No receipt history found for this contract.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        @else
            <div class="text-center py-12">
                <p class="text-lg text-gray-500 dark:text-gray-400">No contracts found to display the ledger.</p>
            </div>
        @endif
    </div>

    {{-- Attachment Modal (Standard HTML + Alpine + Tailwind) --}}
    <div
        x-data="{ show: @entangle('showAttachmentModal').live }"
        x-show="show"
        x-on:keydown.escape.window="show = false; $wire.closeAttachmentModal()" {{-- Close on escape --}}
        style="display: none;" {{-- Hide initially to prevent flash --}}
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                x-on:click="show = false; $wire.closeAttachmentModal()" {{-- Close on background click --}}
                aria-hidden="true"
            ></div>

            {{-- This element is to trick the browser into centering the modal contents. --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal panel --}}
            <div
                x-show="show"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6 dark:bg-gray-800"
                role="document"
            >
                <div>
                    @if ($modalAttachmentUrl)
                        <img src="{{ $modalAttachmentUrl }}" alt="Receipt Attachment" class="max-w-full max-h-[80vh] mx-auto"/>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400">Attachment URL not available.</p>
                    @endif
                </div>
                <div class="mt-5 sm:mt-6">
                    <button
                        type="button"
                        wire:click="closeAttachmentModal"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-primary-600 border border-transparent rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm"
                        x-on:click="show = false"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
