<div x-data="{
        showConfirmationModal: false,
        confirmingActionMessage: '',
        confirmedActionName: null
    }">
    @php
        // Eager load settlement status early for button display
        $contract->loadMissing('settlement');
        $hasSettlement = $contract->settlement !== null;
    @endphp
<div>
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Contract #{{ $contract->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View and manage contract details.
                </p>
            </div>
                <div class="flex items-center space-x-2">
                    {{-- Email Report Button --}}
                    <button wire:click="emailPdfReport" wire:loading.attr="disabled" wire:target="emailPdfReport" type="button" class="p-1 rounded-md bg-white text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:text-gray-100 dark:hover:bg-gray-600">
                        <span class="sr-only">Email Report</span>
                        <div wire:loading wire:target="emailPdfReport" class="animate-spin">
                            <flux:icon name="arrow-path" class="h-5 w-5" />
                        </div>
                        <div wire:loading.remove wire:target="emailPdfReport">
                            <flux:icon name="envelope" class="h-5 w-5" />
                        </div>
                    </button>

                    {{-- Print Report Button --}}
                    <button wire:click="exportToPdf" wire:loading.attr="disabled" wire:target="exportToPdf" type="button" class="p-1 rounded-md bg-white text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:text-gray-100 dark:hover:bg-gray-600">
                        <span class="sr-only">Print Report</span>
                        <div wire:loading wire:target="exportToPdf" class="animate-spin">
                            <flux:icon name="arrow-path" class="h-5 w-5" />
                        </div>
                        <div wire:loading.remove wire:target="exportToPdf">
                            <flux:icon name="printer" class="h-5 w-5" />
                        </div>
                    </button>

                @if($contract->validity === 'YES' && !$contract->renewals()->exists())
                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('renew contracts'))
                        <a href="{{ route('contracts.renew', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-green-500 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-green-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600">
                        <flux:icon name="arrow-path" class="-ml-0.5 h-5 w-5" />
                        Renew Contract
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('edit contracts'))
                        <a href="{{ route('contracts.edit', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-gray-300 px-2.5 py-1.5 text-sm font-semibold text-gray-600 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:hover:bg-gray-600">
                        <flux:icon name="pencil" class="-ml-0.5 h-5 w-5" />
                        Edit Contract
                    </a>
                    @endif

                        {{-- Close Contract Button - Simple close --}}
                        <button type="button"
                                @click="
                                    confirmingActionMessage = 'Are you sure you want to close this contract? This cannot be undone.';
                                    confirmedActionName = 'closeContract';
                                    showConfirmationModal = true;
                                "
                                class="inline-flex items-center gap-x-1.5 rounded-md bg-orange-500 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-500">
                            <flux:icon name="archive-box-x-mark" class="-ml-0.5 h-5 w-5" />
                            Close Contract
                        </button>

                    @if(auth()->user()->hasRole('Super Admin') || auth()->user()->can('terminate contracts'))
                        <a href="{{ route('contracts.terminate', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-red-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        <flux:icon name="x-circle" class="-ml-0.5 h-5 w-5" />
                        Terminate Contract
                    </a>
                    @endif
                @endif
                    <a href="{{ route('contracts.table') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:hover:bg-gray-600">
                    <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                    Back to Contracts
                </a>
                    {{-- Add Settle Button here for Inactive Contracts --}}
                    @if($contract->validity === 'NO' && !$hasSettlement)
                        <a href="{{ route('contracts.settlement.create', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-indigo-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"> {{-- Match padding --}}
                            <flux:icon name="banknotes" class="-ml-0.5 h-5 w-5" />
                            Settle Security Deposit
                        </a>
                    @endif
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
                                    <span class="font-semibold">{{ number_format($contract->amount, 2) }}</span>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        <span>Collection Scheduled: <span class="font-medium">{{ number_format($totalRentScheduled, 2) }}</span></span>
                                        @if($balanceDue > 0)
                                            {{-- balanceDue represents the unscheduled portion --}}
                                            <span class="ml-2">Unscheduled: <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ number_format($balanceDue, 2) }}</span></span>
                                        @endif
                                    </div>
                                    @if($balanceDue > 0)
                                        {{-- Removing the previous unscheduled display as it's moved above --}}
                                    @endif
                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        <span>Realized Amount: <span class="text-green-600 dark:text-green-400 font-medium">{{ number_format($totalRentCleared, 2) }}</span></span>
                                        @if($totalRentPendingClearance > 0)
                                            <span class="ml-2">Balance Pending Realization: <span class="text-red-600 dark:text-red-400 font-medium">{{ number_format($totalRentPendingClearance, 2) }}</span></span>
                                        @endif
                                    </div>
                                </dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Security Deposit</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <div class="flex items-center gap-2">
                                    <span>{{ number_format($contract->sec_amt, 2) }}</span>
                                    @if($this->isSecurityDepositCleared())
                                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </div>
                            </dd>
                        </div>
                        @if($contract->isVatApplicable())
                        <div class="bg-white dark:bg-gray-800 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">VAT Information</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <div class="space-y-1">
                                    <div class="flex justify-between">
                                        <span>VAT Rate:</span>
                                        <span class="font-medium">{{ number_format($contract->getVatRate(), 2) }}%</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>VAT Amount:</span>
                                        <span class="font-medium">{{ number_format($contract->calculateVatAmount(), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between border-t pt-1">
                                        <span class="font-semibold">Total (Incl. VAT):</span>
                                        <span class="font-semibold">{{ number_format($contract->getTotalAmountWithVat(), 2) }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span>VAT Scheduled: <span class="font-medium">{{ number_format($totalVatScheduled, 2) }}</span></span>
                                    @if($vatBalanceDue > 0)
                                        <span class="ml-2">Unscheduled: <span class="text-yellow-600 dark:text-yellow-400 font-medium">{{ number_format($vatBalanceDue, 2) }}</span></span>
                                    @endif
                                </div>
                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    <span>VAT Realized: <span class="text-green-600 dark:text-green-400 font-medium">{{ number_format($totalVatCleared, 2) }}</span></span>
                                    @if($totalVatPendingClearance > 0)
                                        <span class="ml-2">VAT Pending Realization: <span class="text-red-600 dark:text-red-400 font-medium">{{ number_format($totalVatPendingClearance, 2) }}</span></span>
                                    @endif
                                </div>
                            </dd>
                        </div>
                        @endif
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ejari</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->ejari }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Contract Type</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                    @if(strtolower($contract->type) === 'terminated')
                                        <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-900/50 dark:text-red-400">
                                            Terminated
                                        </span>
                                    @else
                                        {{ ucfirst($contract->type) }}
                                    @endif
                                </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ $contract->validity === 'YES' ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/50 dark:text-green-400' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-900/50 dark:text-red-400' }}">
                                    {{ $contract->validity === 'YES' ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                            {{-- Display Settlement Status Here --}}
                            @if($hasSettlement)
                                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Settlement Status</dt>
                                    <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                        <span class="inline-flex items-center gap-x-1.5 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-900/50 dark:text-green-300">
                                            <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                            </svg>
                                            Settled on {{ $contract->settlement->created_at->format('M d, Y') }}
                                        </span>
                                         {{-- Optional: Link to view settlement details --}}
                                         {{-- <a href="{{ route('contracts.settlement.create', $contract) }}" class="ml-2 text-xs text-indigo-600 hover:underline">View Details</a> --}}
                                    </dd>
                                </div>
                            @endif
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
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($prevContract->amount, 2) }}</span>
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
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($renewal->amount, 2) }}</span>
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

    {{-- Confirmation Modal --}}
    <div x-show="showConfirmationModal"
         style="display: none;"
         x-on:keydown.escape.window="showConfirmationModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">

        {{-- Background Overlay --}}
        <div x-show="showConfirmationModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity"
             x-on:click="showConfirmationModal = false">
            <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900 dark:opacity-75"></div>
        </div>

        {{-- Modal Panel --}}
        <div x-show="showConfirmationModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full sm:p-6"
             role="dialog"
             aria-modal="true"
             aria-labelledby="modal-headline">

            <div>
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <!-- Heroicon name: exclamation-triangle -->
                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="mt-3 text-center sm:mt-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-headline">
                        Confirm Action
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="confirmingActionMessage">
                            Are you sure?
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button type="button"
                        @click="if (confirmedActionName) { $wire.call(confirmedActionName) }; showConfirmationModal = false;"
                        class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:col-start-2 sm:text-sm">
                    Confirm
                </button>
                <button type="button"
                        @click="showConfirmationModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    @if($contract->validity === 'NO')
        {{-- Message moved into the details DL below --}}
    @endif
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
