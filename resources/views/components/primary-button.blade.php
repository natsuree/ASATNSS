<button {{ $attributes->merge(['type' => 'submit', 'class' => 'asat-button asat-button-primary focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
