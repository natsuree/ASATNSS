<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="asat-password-wrap mt-1">
                <x-text-input id="password" class="block w-full"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                data-password-strength />
                <x-password-toggle-button target="password" />
            </div>
            <x-password-strength-hint />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="asat-password-wrap mt-1">
                <x-text-input id="password_confirmation" class="block w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <x-password-toggle-button target="password_confirmation" />
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <a class="text-sm font-bold text-slate-600 hover:text-[var(--asat-teal)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
