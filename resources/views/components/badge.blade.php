@props(['variant' => 'gray', 'dot' => false])

@php
    $variants = [
        'dikembalikan' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/60',
        'success'      => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/60',
        'dipinjam'     => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border-amber-200 dark:border-amber-800/60',
        'warning'      => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border-amber-200 dark:border-amber-800/60',
        'terlambat'    => 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border-rose-200 dark:border-rose-800/60',
        'danger'       => 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border-rose-200 dark:border-rose-800/60',
        'indigo'       => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800/60',
        'info'         => 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border-blue-200 dark:border-blue-800/60',
        'gray'         => 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-700',
    ];

    $classes = $variants[$variant] ?? $variants['gray'];

    $dotColors = [
        'dikembalikan' => 'bg-emerald-500',
        'success'      => 'bg-emerald-500',
        'dipinjam'     => 'bg-amber-500',
        'warning'      => 'bg-amber-500',
        'terlambat'    => 'bg-rose-500',
        'danger'       => 'bg-rose-500',
        'indigo'       => 'bg-indigo-500',
        'info'         => 'bg-blue-500',
        'gray'         => 'bg-gray-400',
    ];
    $dotClass = $dotColors[$variant] ?? $dotColors['gray'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border ' . $classes]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
    @endif
    {{ $slot }}
</span>
