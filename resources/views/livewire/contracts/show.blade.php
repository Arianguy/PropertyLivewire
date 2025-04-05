<div>
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contract #{{ $contract->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View contract details and renewal history.
                </p>
            </div>
            <div class="flex space-x-3">
                @if($contract->validity === 'YES' && !$contract->renewals()->exists())
                    <a href="{{ route('contracts.renew', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        <flux:icon name="arrow-path" class="-ml-0.5 h-5 w-5" />
                        Renew Contract
                    </a>
                    <a href="{{ route('contracts.edit', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                        <flux:icon name="pencil" class="-ml-0.5 h-5 w-5" />
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

        <div class="mt-6 space-y-8">
            <!-- Current Contract Details -->
            <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Contract Information</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->tenant->name }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Property</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->property->name }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contract Period</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                {{ $contract->cstart->format('M d, Y') }} - {{ $contract->cend->format('M d, Y') }}
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Rental Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">$ {{ number_format($contract->amount, 2) }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Security Deposit</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">$ {{ number_format($contract->sec_amt, 2) }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ejari Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->ejari ?: 'Not provided' }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contract Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ ucfirst($contract->type) }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="inline-flex items-center rounded-md bg-{{ $contract->validity === 'YES' ? 'green' : 'red' }}-50 px-2 py-1 text-xs font-medium text-{{ $contract->validity === 'YES' ? 'green' : 'red' }}-700 ring-1 ring-inset ring-{{ $contract->validity === 'YES' ? 'green' : 'red' }}-600/20">
                                    {{ $contract->validity === 'YES' ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Contract Documents -->
            @if(count($media) > 0)
            <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Contract Documents</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($media as $file)
                        <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                            <div class="flex w-0 flex-1 items-center">
                                <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                    <span class="truncate font-medium">{{ $file['name'] }}</span>
                                    <span class="flex-shrink-0 text-gray-400">{{ number_format($file['size'] / 1024, 2) }} kb</span>
                                </div>
                            </div>
                            <div class="ml-4 flex flex-shrink-0 space-x-4">
                                <a href="{{ $file['download_url'] }}" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
                                <a href="{{ $file['url'] }}" target="_blank" class="font-medium text-indigo-600 hover:text-indigo-500">View</a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Previous Contracts -->
            @if(count($previousContracts) > 0)
            <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Previous Contracts</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">History of contracts that led to this one.</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($previousContracts as $prevContract)
                        <li class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Contract #{{ $prevContract['name'] }}</h4>
                                    <div class="mt-1 grid grid-cols-2 gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div>
                                            <p>Tenant: {{ $prevContract['tenant'] }}</p>
                                            <p>Property: {{ $prevContract['property'] }}</p>
                                            <p>Period: {{ $prevContract['start_date'] }} - {{ $prevContract['end_date'] }}</p>
                                        </div>
                                        <div>
                                            <p>Amount: $ {{ $prevContract['amount'] }}</p>
                                            <p>Security Deposit: $ {{ $prevContract['security_deposit'] }}</p>
                                            <p>Ejari: {{ $prevContract['ejari'] ?: 'Not provided' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('contracts.show', $prevContract['id']) }}" class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400">View Details</a>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Renewal Contracts -->
            @if(count($renewalContracts) > 0)
            <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Renewal Contracts</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Contracts that were renewed from this one.</p>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($renewalContracts as $renewalContract)
                        <li class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Contract #{{ $renewalContract['name'] }}</h4>
                                    <div class="mt-1 grid grid-cols-2 gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <div>
                                            <p>Tenant: {{ $renewalContract['tenant'] }}</p>
                                            <p>Property: {{ $renewalContract['property'] }}</p>
                                            <p>Period: {{ $renewalContract['start_date'] }} - {{ $renewalContract['end_date'] }}</p>
                                        </div>
                                        <div>
                                            <p>Amount: $ {{ $renewalContract['amount'] }}</p>
                                            <p>Security Deposit: $ {{ $renewalContract['security_deposit'] }}</p>
                                            <p>Ejari: {{ $renewalContract['ejari'] ?: 'Not provided' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('contracts.show', $renewalContract['id']) }}" class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400">View Details</a>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
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
