<div>
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Renew Contract #{{ $contract->name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Create a new contract based on the existing one.
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('contracts.show', $contract) }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                    Back to Contract
                </a>
            </div>
        </div>

        <div class="mt-4">
            <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                <div class="px-4 py-3 sm:px-6">
                    <h3 class="text-base font-medium text-gray-900 dark:text-gray-100">Current Contract Information</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl class="grid grid-cols-1 sm:grid-cols-2">
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tenant</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->tenant->name }}</dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Property</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $contract->property->name }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Period</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                {{ $contract->cstart->format('M d, Y') }} - {{ $contract->cend->format('M d, Y') }}
                            </dd>
                        </div>
                        <div class="bg-white dark:bg-gray-800 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">AED {{ number_format($contract->amount, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <form wire:submit="save" class="mt-4">
                <div class="overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                    <div class="px-4 py-3 sm:px-6">
                        <h3 class="text-base font-medium text-gray-900 dark:text-gray-100">New Contract Details</h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:p-4">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="space-y-1">
                                    <label for="tenant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant</label>
                                    <div class="mt-1 block w-full rounded-md border-gray-300 py-1.5 pl-3 pr-10 text-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" readonly>
                                        {{ $contract->tenant->name }}
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label for="property_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Property</label>
                                    <div class="mt-1 block w-full rounded-md border-gray-300 py-1.5 pl-3 pr-10 text-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100" readonly>
                                        {{ $contract->property->name }}
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label for="cstart" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                                    <input type="date" wire:model="cstart" id="cstart" class="mt-1 block w-full rounded-md border-gray-300 py-1.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm">
                                    @error('cstart') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label for="cend" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                                    <input type="date" wire:model="cend" id="cend" class="mt-1 block w-full rounded-md border-gray-300 py-1.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm">
                                    @error('cend') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rental Amount</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">AED</span>
                                        </div>
                                        <input type="number" wire:model="amount" id="amount" class="block w-full rounded-md border-gray-300 py-1.5 pl-12 pr-12 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm" placeholder="0.00">
                                    </div>
                                    @error('amount') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label for="sec_amt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Security Deposit</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">AED</span>
                                        </div>
                                        <input type="number" wire:model="sec_amt" id="sec_amt" class="block w-full rounded-md border-gray-300 py-1.5 pl-12 pr-12 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm" placeholder="0.00">
                                    </div>
                                    @error('sec_amt') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label for="ejari" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ejari Number</label>
                                    <input type="text" wire:model="ejari" id="ejari" class="mt-1 block w-full rounded-md border-gray-300 py-1.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:text-sm">
                                    @error('ejari') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-1">
                                    <label for="cont_copy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contract Documents</label>
                                    <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 dark:border-gray-600 px-4 py-3">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-8 w-8 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                                <label for="cont_copy" class="relative cursor-pointer rounded-md bg-white dark:bg-gray-800 font-medium text-indigo-600 dark:text-indigo-400 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                                    <span>Upload files</span>
                                                    <input type="file" wire:model="cont_copy" id="cont_copy" class="sr-only" multiple>
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">PDF, DOC, DOCX, JPG, PNG up to 10MB</p>
                                        </div>
                                    </div>
                                    @error('cont_copy') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:ml-3 sm:w-auto">
                            Renew Contract
                        </button>
                        <a href="{{ route('contracts.show', $contract) }}" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
