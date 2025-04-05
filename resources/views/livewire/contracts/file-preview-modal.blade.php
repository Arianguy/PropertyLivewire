<!-- File Preview Modal -->
<div
    x-data="{
        show: false,
        url: '',
        filename: '',
        isImage: false,
        isPdf: false
    }"
    @open-modal.window="
        show = true;
        url = $event.detail.url;
        filename = $event.detail.filename;
        isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(filename.split('.').pop().toLowerCase());
        isPdf = filename.split('.').pop().toLowerCase() === 'pdf';
    "
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <!-- Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" x-text="filename"></h3>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="flex-grow overflow-auto p-4 flex items-center justify-center">
                    <!-- Image preview -->
                    <template x-if="isImage">
                        <img :src="url" :alt="filename" class="max-w-full max-h-[60vh] object-contain">
                    </template>

                    <!-- PDF preview -->
                    <template x-if="isPdf">
                        <iframe :src="url" class="w-full h-[60vh]" frameborder="0"></iframe>
                    </template>

                    <!-- Other file types -->
                    <template x-if="!isImage && !isPdf">
                        <div class="text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-700 dark:text-gray-300">This file type cannot be previewed.</p>
                            <a :href="url + '/download'" class="inline-flex mt-4 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Download File
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
