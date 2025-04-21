<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Create New Payment</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Record a new payment made for a property.
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 dark:hover:bg-gray-700">
                <flux:icon name="arrow-left" class="-ml-0.5 h-5 w-5" />
                Back to Payments
            </a>
        </div>
    </div>

    <form wire:submit.prevent="save" class="mt-6 space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2">

                {{-- Property --}}
                <div>
                    <label for="property_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Property*</label>
                    <div class="mt-2">
                        <select wire:model.live="property_id" id="property_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('property_id') ring-red-500 @enderror">
                            <option value="">Select Property</option>
                            @foreach($properties as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('property_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Contract (Optional) --}}
                <div>
                    <label for="contract_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Contract (Optional)</label>
                    <div class="mt-2">
                         <select wire:model="contract_id" id="contract_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('contract_id') ring-red-500 @enderror" @if($contracts->isEmpty()) disabled @endif>
                            <option value="">Select Contract (if applicable)</option>
                            @foreach($contracts as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($contracts->isEmpty() && !empty($property_id))
                            <span class="text-gray-500 text-xs mt-1">No relevant contracts found for selected property.</span>
                        @endif
                    </div>
                     @error('contract_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Payment Type --}}
                <div>
                    <label for="payment_type_id" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Payment Type*</label>
                    <div class="mt-2">
                        <select wire:model="payment_type_id" id="payment_type_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('payment_type_id') ring-red-500 @enderror">
                            <option value="">Select Payment Type</option>
                            @foreach($paymentTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('payment_type_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Amount --}}
                <div>
                    <label for="amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Amount*</label>
                     <div class="mt-2 relative rounded-md shadow-sm">
                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                             {{-- Add currency symbol if desired --}}
                             {{-- <span class="text-gray-500 sm:text-sm">$</span> --}}
                         </div>
                         <input type="number" step="0.01" wire:model="amount" id="amount" class="block w-full rounded-md border-0 py-1.5 pl-3 pr-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('amount') ring-red-500 @enderror" placeholder="0.00">
                     </div>
                    @error('amount') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Paid At Date --}}
                <div>
                    <label for="paid_at" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Payment Date*</label>
                    <div class="mt-2">
                        <input type="date" wire:model="paid_at" id="paid_at" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('paid_at') ring-red-500 @enderror">
                    </div>
                     @error('paid_at') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Description</label>
                    <div class="mt-2">
                         <textarea wire:model="description" id="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-gray-100 dark:ring-gray-700 @error('description') ring-red-500 @enderror"></textarea>
                    </div>
                    @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Attachment Upload --}}
                 <div class="sm:col-span-2">
                    <label for="attachment" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Attachment (Optional)</label>
                    <div class="mt-2">
                        <input type="file" wire:model="attachment" id="attachment" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 @error('attachment') border-red-500 @enderror"
                         aria-describedby="attachment_help">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-300" id="attachment_help">PDF, DOC, DOCX, JPG, PNG (MAX. 10MB).</p>
                        <div wire:loading wire:target="attachment" class="text-sm text-primary-600 mt-1">Uploading...</div>
                    </div>
                    @error('attachment') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                    {{-- Preview for image files --}}
                    @if ($attachment && str_starts_with($attachment->getMimeType(), 'image/'))
                        <div class="mt-4">
                            <img src="{{ $attachment->temporaryUrl() }}" class="max-h-40 rounded border border-gray-200 dark:border-gray-700">
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="pt-5">
            <div class="flex justify-end gap-x-3">
                 <a href="{{ route('payments.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-100 dark:ring-gray-600 dark:hover:bg-gray-600">Cancel</a>
                <button type="submit" class="inline-flex justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                    <span wire:loading wire:target="save">Saving...</span>
                    <span wire:loading.remove wire:target="save">Save Payment</span>
                </button>
            </div>
        </div>
    </form>
</div>
