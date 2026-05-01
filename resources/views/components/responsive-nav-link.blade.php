@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-teal-600 text-start text-base font-black text-[var(--asat-teal)] bg-teal-50 focus:outline-none focus:text-[var(--asat-navy)] focus:bg-teal-100 focus:border-teal-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-bold text-slate-600 hover:text-[var(--asat-navy)] hover:bg-slate-50 hover:border-teal-300 focus:outline-none focus:text-[var(--asat-navy)] focus:bg-slate-50 focus:border-teal-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
