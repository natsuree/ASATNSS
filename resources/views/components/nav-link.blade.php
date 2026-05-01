@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-teal-600 text-sm font-black leading-5 text-[var(--asat-ink)] focus:outline-none focus:border-teal-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-slate-500 hover:text-[var(--asat-navy)] hover:border-teal-300 focus:outline-none focus:text-[var(--asat-navy)] focus:border-teal-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
