<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="asat-password-wrap mt-1">
                <x-text-input id="password" class="block w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <x-password-toggle-button target="password" />
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-teal-700 shadow-sm focus:ring-teal-600" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-slate-600 hover:text-[var(--asat-teal)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <p class="mt-5 text-center text-sm font-semibold text-slate-500">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" class="font-black text-[var(--asat-teal)] hover:text-[var(--asat-navy)]">
                {{ __('Register') }}
            </a>
        </p>
    </form>
</x-guest-layout>
