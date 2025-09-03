<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Sell Property: {{ $property->name }}
            </h2>
            <p class="text-red-100 mt-1">Complete the sale process and archive the property</p>
        </div>

        <!-- Property Information -->
        <div class="px-6 py-4 bg-gray-50 border-b">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm font-medium text-gray-500">Property ID:</span>
                    <p class="text-gray-900">{{ $property->id }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Current Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                        @if($property->status === 'VACANT') bg-yellow-100 text-yellow-800
                        @elseif($property->status === 'LEASED') bg-green-100 text-green-800
                        @elseif($property->status === 'MAINTENANCE') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $property->status }}
                    </span>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Purchase Value:</span>
                    <p class="text-gray-900">{{ number_format($property->purchase_value) }}</p>
                </div>
            </div>
        </div>

        @if(!$showConfirmation)
            <!-- Sale Form -->
            <form wire:submit="showSaleConfirmation" class="p-6">
                <!-- Property Status Display -->
                <div class="mb-6 p-4 rounded-lg {{ $property->status === 'VACANT' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($property->status === 'VACANT')
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium {{ $property->status === 'VACANT' ? 'text-green-800' : 'text-red-800' }}">
                                Property Status: {{ $property->status }}
                            </h3>
                            <p class="text-sm {{ $property->status === 'VACANT' ? 'text-green-700' : 'text-red-700' }}">
                                @if($property->status === 'VACANT')
                                    This property is eligible for sale.
                                @else
                                    Only vacant properties can be sold. Please ensure the property status is VACANT before proceeding.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sale Date -->
                    <div>
                        <label for="sale_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Sale Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="sale_date"
                               wire:model="sale_date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               max="{{ now()->format('Y-m-d') }}">
                        @error('sale_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sale Price -->
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Sale Price <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="sale_price"
                               wire:model="sale_price" 
                               step="0.01"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Enter sale price">
                        @error('sale_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buyer Name -->
                    <div class="md:col-span-2">
                        <label for="buyer_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Buyer Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="buyer_name"
                               wire:model="buyer_name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Enter buyer's full name">
                        @error('buyer_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sale Notes -->
                    <div class="md:col-span-2">
                        <label for="sale_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Sale Notes
                        </label>
                        <textarea id="sale_notes"
                                  wire:model="sale_notes" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Enter any additional notes about the sale..."></textarea>
                        @error('sale_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                            <p class="text-sm text-yellow-700 mt-1">
                                 This action will mark the property as SOLD and move it to archived status. 
                                 Only vacant properties can be sold. This action cannot be undone.
                             </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('properties.show', $property) }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            {{ $property->status !== 'VACANT' ? 'disabled' : '' }}
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $property->status === 'VACANT' ? 'bg-red-600 hover:bg-red-700' : 'bg-gray-400 cursor-not-allowed' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                        <span wire:loading.remove>
                            {{ $property->status === 'VACANT' ? 'Proceed to Confirmation' : 'Property Must Be Vacant' }}
                        </span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </form>
        @else
            <!-- Confirmation Modal Content -->
            <div class="p-6">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Confirm Property Sale</h3>
                    <p class="mt-2 text-sm text-gray-500">
                        Please review the sale details below before confirming. This action cannot be undone.
                    </p>
                </div>

                <!-- Sale Summary -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Sale Summary</h4>
                    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Property:</dt>
                            <dd class="text-sm text-gray-900">{{ $property->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sale Date:</dt>
                            <dd class="text-sm text-gray-900">{{ Carbon\Carbon::parse($sale_date)->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sale Price:</dt>
                            <dd class="text-sm text-gray-900">{{ number_format($sale_price, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Buyer:</dt>
                            <dd class="text-sm text-gray-900">{{ $buyer_name }}</dd>
                        </div>
                        @if($sale_notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Notes:</dt>
                            <dd class="text-sm text-gray-900">{{ $sale_notes }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>



                <!-- Confirmation Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            wire:click="cancelSale"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Go Back
                    </button>
                    <button type="button" 
                            wire:click="confirmSale"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                        <span wire:loading.remove>Confirm Sale</span>
                        <span wire:loading>Processing Sale...</span>
                    </button>
                </div>
            </div>
        @endif

        @if($saleConfirmed)
        <!-- Sale Completed Message -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        Property Sale Completed Successfully!
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>The property has been marked as sold and all active contracts have been closed.</p>
                    </div>
                </div>
            </div>
        </div>


        @endif
    </div>
</div>