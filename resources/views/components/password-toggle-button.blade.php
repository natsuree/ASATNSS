@props(['target'])

<button
    type="button"
    class="asat-password-toggle"
    data-password-toggle="{{ $target }}"
    aria-label="{{ __('Show password') }}"
    aria-pressed="false"
>
    <span data-password-icon="show" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
            <circle cx="12" cy="12" r="3" />
        </svg>
    </span>
    <span data-password-icon="hide" class="hidden" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
            <path d="m3 3 18 18" />
            <path d="M10.6 10.7A3 3 0 0 0 12 15a3 3 0 0 0 2.3-5.2" />
            <path d="M9.4 5.1A10.9 10.9 0 0 1 12 5c6.5 0 10 7 10 7a17.7 17.7 0 0 1-4 4.9" />
            <path d="M6.6 6.7C4.3 8.2 2.9 10.6 2 12c0 0 3.5 7 10 7 1.8 0 3.3-.4 4.7-1" />
        </svg>
    </span>
    <span class="sr-only" data-password-toggle-text>{{ __('Show password') }}</span>
</button>
