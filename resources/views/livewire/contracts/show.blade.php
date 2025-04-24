<div>
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contract #{{ $contract->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View and manage contract details.
                </p>
            </div>
            <div class="flex space-x-3">
                @if($contract->validity === 'YES' && !$contract->renewals()->exists())
                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('renew contracts'))
                    <a href="{{ route('contracts.renew', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        <flux:icon name="arrow-path" class="-ml-0.5 h-5 w-5" />
                        Renew Contract
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit contracts'))
                    <a href="{{ route('contracts.edit', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:hover:bg-gray-600">
                        <flux:icon name="pencil" class="-ml-0.5 h-5 w-5" />
                        Edit Contract
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('terminate contracts'))
                    <a href="{{ route('contracts.terminate', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        <flux:icon name="x-circle" class="-ml-0.5 h-5 w-5" />
                        Terminate Contract
                    </a>
                    @endif
                @endif
                <a href="{{ route('contracts.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:hover:bg-gray-600">
                    <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                    Back to Contracts
                </a>
            </div>
        </div>

        <div class="mt-4">
            <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-2 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Contract Information</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->tenant->name }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Property</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->property->name }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contract Period</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                {{ $contract->cstart->format('M d, Y') }} - {{ $contract->cend->format('M d, Y') }}
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rental Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="font-semibold">${{ number_format($contract->amount, 2) }}</span>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span>Collection Scheduled: <span class="font-medium">${{ number_format($totalRentScheduled, 2) }}</span></span>
                                    @if($balanceDue > 0)
                                        {{-- balanceDue represents the unscheduled portion --}}
                                        <span class="ml-2">Unscheduled: <span class="text-yellow-600 dark:text-yellow-400 font-medium">${{ number_format($balanceDue, 2) }}</span></span>
                                    @endif
                                </div>
                                @if($balanceDue > 0)
                                    {{-- Removing the previous unscheduled display as it's moved above --}}
                                @endif
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span>Realized Amount: <span class="text-green-600 dark:text-green-400 font-medium">${{ number_format($totalRentCleared, 2) }}</span></span>
                                    @if($totalRentPendingClearance > 0)
                                        <span class="ml-2">Balance Pending Realization: <span class="text-red-600 dark:text-red-400 font-medium">${{ number_format($totalRentPendingClearance, 2) }}</span></span>
                                    @endif
                                </div>
                            </dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Security Deposit</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">${{ number_format($contract->sec_amt, 2) }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ejari</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->ejari }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contract Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ ucfirst($contract->type) }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $contract->validity === 'YES' ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/50 dark:text-green-400' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-900/50 dark:text-red-400' }}">
                                    {{ $contract->validity === 'YES' ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                        @if($contract->termination_reason)
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Termination Reason</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->termination_reason }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Embed Receipt History Here in a Card -->
            <div class="mt-6 overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Receipt History</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-5 sm:p-0">
                     @livewire('receipts.contract-receipts', ['contract' => $contract], key('receipts-for-contract-' . $contract->id))
                </div>
            </div>

            @if($contract->getMedia('contracts_copy')->count() > 0)
            <div class="mt-6">
                <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Contract Documents</h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($contract->getMedia('contracts_copy') as $media)
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $media->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ human_filesize($media->size) }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('media.download', $media->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                @if($contract->allAncestors()->isNotEmpty())
                <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Previous Contracts</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($contract->allAncestors() as $prevContract)
                        <div class="p-3">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('contracts.show', $prevContract) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Contract #{{ $prevContract->name }}
                                </a>
                                <span class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($prevContract->amount, 2) }}</span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $prevContract->cstart->format('M d, Y') }} - {{ $prevContract->cend->format('M d, Y') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($contract->allRenewals()->isNotEmpty())
                <div class="overflow-hidden bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Renewal Contracts</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($contract->allRenewals() as $renewal)
                        <div class="p-3">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('contracts.show', $renewal) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    Contract #{{ $renewal->name }}
                                </a>
                                <span class="text-sm text-gray-500 dark:text-gray-400">${{ number_format($renewal->amount, 2) }}</span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $renewal->cstart->format('M d, Y') }} - {{ $renewal->cend->format('M d, Y') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
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
