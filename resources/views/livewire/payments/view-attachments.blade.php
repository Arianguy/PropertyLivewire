<div
    x-data="{ show: @entangle('showModal') }"
    x-show="show"
    x-cloak
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="attachment-modal-title"
    role="dialog"
    aria-modal="true"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity dark:bg-gray-900 dark:bg-opacity-75"
             @click="show = false"
             aria-hidden="true">
        </div>

        <!-- Centered modal content -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6 dark:bg-gray-800">

            <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="attachment-modal-title">
                        Payment Attachments
                    </h3>
                    @if($payment)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                            For Payment ID: {{ $payment->id }} (Amount: {{ number_format($payment->amount, 2) }})
                        </p>
                    @endif

                    <div class="mt-4 space-y-3 max-h-60 overflow-y-auto">
                        @if($payment && isset($attachments) && $attachments->count() > 0)
                            @foreach($attachments as $attachment)
                                <div class="flex items-center justify-between p-2 border border-gray-200 dark:border-gray-700 rounded-md">
                                    <div class="flex items-center space-x-2 truncate">
                                        <flux:icon name="document-text" class="h-5 w-5 text-gray-400 flex-shrink-0" />
                                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate" title="{{ $attachment->file_name }}">
                                            {{ $attachment->file_name }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ $attachment->human_readable_size }})</span>
                                    </div>
                                    <div class="flex space-x-2 flex-shrink-0">
                                        {{-- View Link (opens in new tab) --}}
                                        <a href="{{ $attachment->getUrl() }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                            <flux:icon name="eye" class="h-5 w-5" />
                                        </a>
                                        {{-- Download Link --}}
                                        {{-- Note: Spatie default getUrl might not force download. Consider a dedicated download route if needed. --}}
                                        <a href="{{ $attachment->getUrl() }}" download class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Download">
                                            <flux:icon name="arrow-down-tray" class="h-5 w-5" />
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">No attachments found for this payment.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-6">
                <button type="button" wire:click="closeModal" @click="show = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
