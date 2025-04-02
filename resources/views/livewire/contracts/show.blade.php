<div>
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contract #{{ $contract->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Detailed information about this contract and its history.
                </p>
            </div>
            <div class="flex space-x-3">
                @if($contract->validity === 'YES' && !$contract->renewals()->exists())
                    <a href="{{ route('contracts.renew', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        <flux:icon name="arrow-path" class="-ml-0.5 h-5 w-5" />
                        Renew Contract
                    </a>
                    <a href="{{ route('contracts.edit', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                        <flux:icon name="pencil-square" class="-ml-0.5 h-5 w-5" />
                        Edit Contract
                    </a>
                    <button type="button" wire:click="terminateContract" wire:confirm="Are you sure you want to terminate this contract? The property will be marked as VACANT." class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-600">
                        <flux:icon name="no-symbol" class="-ml-0.5 h-5 w-5" />
                        Terminate
                    </button>
                @endif
                <a href="{{ route('contracts.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                    Back to Contracts
                </a>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- Contract Details -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Contract Information</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Details about the contract terms and conditions.</p>
                    </div>
                    <div>
                        @if($contract->validity === 'YES')
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contract Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->name }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->tenant->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Property</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->property->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Start Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ \Carbon\Carbon::parse($contract->cstart)->format('F d, Y') }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">End Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ \Carbon\Carbon::parse($contract->cend)->format('F d, Y') }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rental Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">${{ number_format($contract->amount, 2) }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Security Deposit</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">${{ number_format($contract->sec_amt, 2) }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-900 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ejari Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                @if($contract->ejari === 'YES')
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                        No
                                    </span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contract Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                @if($contract->previousContract)
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                        Renewal
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20">
                                        Original
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Contract Documents -->
            <div class="lg:col-span-1 bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Contract Documents</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Attached files and media.</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($media as $item)
                            <li class="px-4 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if(Str::contains($item['mime_type'], 'pdf'))
                                            <flux:icon name="document-text" class="h-8 w-8 text-gray-500" />
                                        @else
                                            <flux:icon name="photo" class="h-8 w-8 text-gray-500" />
                                        @endif
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['file_name'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ human_filesize($item['size']) }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ $item['url'] }}" target="_blank" class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                                            <flux:icon name="eye" class="h-4 w-4" />
                                            <span class="sr-only">View</span>
                                        </a>
                                        <a href="{{ $item['download_url'] }}" class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                                            <flux:icon name="arrow-down-tray" class="h-4 w-4" />
                                            <span class="sr-only">Download</span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No documents attached to this contract.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contract History -->
        @if(count($ancestors) > 0 || count($renewals) > 0)
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-900 shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Contract History</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">Previous and subsequent contracts related to this property.</p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 sm:pl-6">Contract #</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Period</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Amount</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Status</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">Type</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                    <!-- Previous Contracts -->
                                    @foreach($ancestors as $ancestor)
                                        <tr class="bg-yellow-50 dark:bg-yellow-900">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $ancestor->name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($ancestor->cstart)->format('M d, Y') }} -
                                                {{ \Carbon\Carbon::parse($ancestor->cend)->format('M d, Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">${{ number_format($ancestor->amount, 2) }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                @if($ancestor->validity === 'YES')
                                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Previous</td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <a href="{{ route('contracts.show', $ancestor) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                                    <flux:icon name="eye" class="h-5 w-5" />
                                                    <span class="sr-only">View</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- Current Contract -->
                                    <tr class="bg-green-50 dark:bg-green-900">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $contract->name }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($contract->cstart)->format('M d, Y') }} -
                                            {{ \Carbon\Carbon::parse($contract->cend)->format('M d, Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">${{ number_format($contract->amount, 2) }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            @if($contract->validity === 'YES')
                                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Current</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                Current
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- Renewal Contracts -->
                                    @foreach($renewals as $renewal)
                                        <tr class="bg-blue-50 dark:bg-blue-900">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-gray-100 sm:pl-6">{{ $renewal->name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($renewal->cstart)->format('M d, Y') }} -
                                                {{ \Carbon\Carbon::parse($renewal->cend)->format('M d, Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">${{ number_format($renewal->amount, 2) }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                @if($renewal->validity === 'YES')
                                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-gray-400">Renewal</td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <a href="{{ route('contracts.show', $renewal) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-500 dark:hover:text-primary-400">
                                                    <flux:icon name="eye" class="h-5 w-5" />
                                                    <span class="sr-only">View</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@php
function human_filesize($size, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $step = 1024;
    $i = 0;
    while ($size > $step) {
        $size = $size / $step;
        $i++;
    }
    return round($size, $precision) . ' ' . $units[$i];
}
@endphp
