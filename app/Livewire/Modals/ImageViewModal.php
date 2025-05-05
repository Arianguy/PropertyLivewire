<?php

namespace App\Livewire\Modals;

use Livewire\Component;
use Livewire\Attributes\On;

class ImageViewModal extends Component
{
    public ?string $imageUrl = null;
    public bool $show = false;

    #[On('showImageModal')]
    public function showModal(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
        $this->show = true;
    }

    public function closeModal(): void
    {
        $this->show = false;
        $this->imageUrl = null;
        $this->dispatch('imageModalClosed');
    }

    public function render()
    {
        return <<<'BLADE'
            <div
                x-data="{ show: $wire.entangle('show'), currentImageUrl: $wire.entangle('imageUrl') }"
                x-show="show"
                x-on:keydown.escape.window="show = false; $wire.closeModal()"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
                style="display: none;"
            >
                <div
                    x-show="show"
                    x-on:click.away="show = false; $wire.closeModal()"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative bg-white rounded-lg shadow-xl overflow-hidden max-w-2xl w-full dark:bg-gray-800"
                >
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Cheque Image
                        </h3>
                        <button
                            type="button"
                            x-on:click="show = false; $wire.closeModal()"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-4">
                        <template x-if="currentImageUrl">
                            <img :src="currentImageUrl" alt="Cheque Image" class="w-full h-auto rounded-md object-contain max-h-[70vh]">
                        </template>
                        <template x-if="!currentImageUrl">
                            <p class="text-center text-gray-500 dark:text-gray-400">Loading image...</p>
                        </template>
                    </div>

                    {{-- Modal Footer (optional) --}}
                     <div class="flex items-center justify-end p-4 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button
                            type="button"
                            x-on:click="show = false; $wire.closeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        BLADE;
    }
}
