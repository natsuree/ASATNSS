<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ASATNSS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="asat-auth-shell flex min-h-screen items-center justify-center px-4 py-8">
            <div class="grid w-full max-w-5xl gap-8 lg:grid-cols-[1fr_430px] lg:items-center">
                <div class="asat-auth-copy hidden lg:block">
                    <div class="flex items-center gap-3">
                        <span class="asat-brand-mark bg-white text-[var(--asat-navy)]">A</span>
                        <div>
                            <p class="text-xl font-extrabold leading-tight">ASATNSS</p>
                            <p class="text-sm font-semibold opacity-80">Scholarship Management Portal</p>
                        </div>
                    </div>

                    <h1 class="mt-10 max-w-2xl text-4xl font-extrabold leading-tight">
                        Automated Scholarship Application Tracking and Notification System
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-7 opacity-90">
                        A centralized portal for student applications, admin review, webhook intake, and status notifications.
                    </p>
                </div>

                <div>
                    <a href="/" class="mb-5 flex items-center justify-center gap-3 text-white lg:hidden">
                        <span class="asat-brand-mark bg-white text-[var(--asat-navy)]">A</span>
                        <span class="font-extrabold">ASATNSS</span>
                    </a>

                    <div class="asat-auth-panel px-6 py-6 sm:px-8">
                        <div class="mb-6 text-center">
                            <div class="mx-auto mb-3 inline-grid h-12 w-12 place-items-center rounded-lg bg-[var(--asat-navy)] text-lg font-extrabold text-white">A</div>
                            <h2 class="text-xl font-extrabold text-[var(--asat-ink)]">ASATNSS Portal</h2>
                            <p class="mt-1 text-sm text-slate-500">Secure scholarship account access</p>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
