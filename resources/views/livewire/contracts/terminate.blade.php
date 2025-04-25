@php
if (!auth()->user()->hasRole('Super Admin') && !auth()->user()->can('terminate contracts')) {
    header('Location: ' . route('contracts.show', $contract));
    exit;
}
@endphp

<div>
    <div class="mx-auto max-w-3xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Terminate Contract #{{ $contract->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Please provide the termination details below.
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('contracts.show', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                    Back to Contract
                </a>
            </div>
        </div>

        <div class="mt-6">
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
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Period</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                {{ $contract->cstart->format('M d, Y') }} - {{ $contract->cend->format('M d, Y') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <form wire:submit="terminate" class="mt-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <!-- Close Date -->
                    <div>
                        <label for="close_date" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Close Date</label>
                        <div class="mt-2">
                            <input type="date" wire:model="close_date" id="close_date" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700">
                        </div>
                        @error('close_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Final Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Final Amount</label>
                        <div class="mt-2 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" wire:model="amount" id="amount" step="0.01" min="0" class="block w-full rounded-md border-0 py-1.5 pl-7 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="0.00">
                        </div>
                        @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Termination Reason -->
                    <div class="sm:col-span-3">
                        <label for="reason" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Termination Reason</label>
                        <div class="mt-2">
                            <textarea wire:model="reason" id="reason" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700" placeholder="Enter the reason for termination"></textarea>
                        </div>
                        @error('reason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Pending Cheques Section -->
                @if($pendingCheques->isNotEmpty())
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Cancel Pending Cheques</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            Select any pending cheques associated with this contract that should be marked as CANCELLED upon termination.
                        </p>
                        <fieldset>
                            <legend class="sr-only">Pending Cheques</legend>
                            <div class="space-y-3">
                                @foreach($pendingCheques as $cheque)
                                    <div class="relative flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input id="cheque_{{ $cheque->id }}"
                                                   wire:model.live="chequesToCancel"
                                                   value="{{ $cheque->id }}"
                                                   type="checkbox"
                                                   class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600 dark:bg-gray-700 dark:border-gray-600 dark:checked:bg-primary-600 dark:focus:ring-offset-gray-800">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="cheque_{{ $cheque->id }}" class="font-medium text-gray-900 dark:text-gray-100">
                                                Cheque #{{ $cheque->cheque_no }} - ${{ number_format($cheque->amount, 2) }}
                                            </label>
                                            <p class="text-gray-500 dark:text-gray-400">Due: {{ $cheque->cheque_date ? \Carbon\Carbon::parse($cheque->cheque_date)->format('M d, Y') : 'N/A' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                @endif

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('contracts.show', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-x-1.5 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        <flux:icon name="x-circle" class="-ml-0.5 h-5 w-5" />
                        Terminate Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
