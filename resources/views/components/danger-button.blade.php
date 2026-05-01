<button {{ $attributes->merge(['type' => 'submit', 'class' => 'asat-button bg-red-700 text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
