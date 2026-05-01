<button {{ $attributes->merge(['type' => 'button', 'class' => 'asat-button asat-button-secondary focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
