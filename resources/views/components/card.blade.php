@props(['title' => null, 'description' => null, 'actions' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden transition duration-150']) }}>
    @if ($title || $actions)
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
            <div>
                @if ($title)
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white leading-none">
                        {{ $title }}
                    </h3>
                @endif
                @if ($description)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $description }}
                    </p>
                @endif
            </div>
            @if ($actions)
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>
</div>
