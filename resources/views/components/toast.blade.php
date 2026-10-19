<div
    x-data="{
        show: true,
        init() {
            setTimeout(() => { this.show = false }, 4500)
        }
    }"
    x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/80 shadow-lg rounded-xl pointer-events-auto p-4 flex items-start gap-3"
>
    @if (session('success'))
        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div class="flex-1 pt-0.5">
            <p class="text-sm font-semibold text-gray-900 dark:text-white">Berhasil!</p>
            <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">{{ session('success') }}</p>
        </div>
    @elseif (session('error'))
        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div class="flex-1 pt-0.5">
            <p class="text-sm font-semibold text-gray-900 dark:text-white">Terjadi Kesalahan</p>
            <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">{{ session('error') }}</p>
        </div>
    @endif

    <button @click="show = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>
