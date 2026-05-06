<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ASATNSS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <main class="asat-auth-shell asat-auth-centered">
            <div class="asat-auth-card">
                <a href="{{ url('/') }}" class="asat-auth-brand">
                    <span class="asat-brand-mark">A</span>
                    <span>
                        <span class="asat-auth-brand-title">ASATNSS</span>
                        <span class="asat-auth-brand-subtitle">Scholarship Management Portal</span>
                    </span>
                </a>

                <div class="asat-auth-card-head">
                    <p class="asat-page-kicker">Secure account access</p>
                    <h1>Automated Scholarship Application Tracking and Notification System</h1>
                </div>

                {{ $slot }}
            </div>
        </main>
    </body>
</html>
