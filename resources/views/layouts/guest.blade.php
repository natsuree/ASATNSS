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
            <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[1.05fr_440px] lg:items-center">
                <div class="asat-auth-copy hidden lg:block">
                    <a href="/" class="inline-flex items-center gap-3">
                        <span class="asat-brand-mark bg-white text-[var(--asat-navy)]">A</span>
                        <span>
                            <span class="block text-xl font-black leading-tight">ASATNSS</span>
                            <span class="block text-sm font-bold opacity-80">Scholarship Management Portal</span>
                        </span>
                    </a>

                    <div class="mt-10">
                        <p class="asat-page-kicker text-white/80">Final project system</p>
                        <h1 class="mt-3 max-w-2xl text-4xl font-black leading-tight">
                            Automated Scholarship Application Tracking and Notification System
                        </h1>
                    </div>

                    <div class="asat-auth-visual mt-8 p-5">
                        <div class="asat-mini-window p-4">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                <div>
                                    <p class="text-xs font-black uppercase text-[var(--asat-teal)]">Live review board</p>
                                    <p class="text-lg font-black text-[var(--asat-ink)]">Scholarship Intake</p>
                                </div>
                                <span class="asat-badge asat-badge-pending">Pending</span>
                            </div>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between rounded-md bg-slate-50 px-4 py-3">
                                    <div>
                                        <p class="font-bold text-slate-900">Student application</p>
                                        <p class="text-xs text-slate-500">Internal form</p>
                                    </div>
                                    <span class="h-2.5 w-2.5 rounded-full bg-[var(--asat-green)]"></span>
                                </div>
                                <div class="flex items-center justify-between rounded-md bg-slate-50 px-4 py-3">
                                    <div>
                                        <p class="font-bold text-slate-900">Tally webhook</p>
                                        <p class="text-xs text-slate-500">External intake</p>
                                    </div>
                                    <span class="h-2.5 w-2.5 rounded-full bg-[var(--asat-sky)]"></span>
                                </div>
                                <div class="flex items-center justify-between rounded-md bg-slate-50 px-4 py-3">
                                    <div>
                                        <p class="font-bold text-slate-900">Email reputation</p>
                                        <p class="text-xs text-slate-500">Abstract API</p>
                                    </div>
                                    <span class="h-2.5 w-2.5 rounded-full bg-[var(--asat-gold)]"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <a href="/" class="mb-5 flex items-center justify-center gap-3 text-white lg:hidden">
                        <span class="asat-brand-mark bg-white text-[var(--asat-navy)]">A</span>
                        <span class="font-black">ASATNSS</span>
                    </a>

                    <div class="asat-auth-panel px-6 py-6 sm:px-8">
                        <div class="mb-6 text-center">
                            <div class="mx-auto mb-3 inline-grid h-12 w-12 place-items-center rounded-lg bg-[var(--asat-navy)] text-lg font-black text-white">A</div>
                            <h2 class="text-xl font-black text-[var(--asat-ink)]">ASATNSS Portal</h2>
                            <p class="mt-1 text-sm font-semibold text-slate-500">Scholarship account access</p>
                        </div>

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
