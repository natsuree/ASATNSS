<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'ASATNSS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="asat-public-page font-sans antialiased">
        @php
            $user = auth()->user();
            $workspaceRoute = $user
                ? ($user->is_admin ? route('admin.applications.index') : route('dashboard'))
                : route('login');
        @endphp

        <div class="asat-public-home">
            <header class="asat-public-home-nav">
                <div class="container px-4">
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <a href="{{ url('/') }}" class="d-flex align-items-center gap-3 text-decoration-none">
                            <span class="asat-brand-mark">A</span>
                            <span>
                                <span class="d-block asat-public-brand-title">ASATNSS</span>
                                <span class="d-block asat-public-brand-subtitle">Scholarship Management Portal</span>
                            </span>
                        </a>

                        <nav class="d-none d-md-flex align-items-center gap-3">
                            @auth
                                <a href="{{ $workspaceRoute }}" class="asat-public-link">Dashboard</a>
                                @if ($user->is_admin)
                                    <a href="{{ route('admin.applications.index') }}" class="asat-public-link">Admin Review</a>
                                @else
                                    <a href="{{ route('applications.index') }}" class="asat-public-link">Applications</a>
                                @endif
                            @else
                                <a href="{{ url('/') }}" class="asat-public-link">Home</a>
                                <a href="{{ route('login') }}" class="asat-public-link">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="asat-public-link">Register</a>
                                @endif
                            @endauth
                        </nav>
                    </div>
                </div>
            </header>

            <main class="asat-public-home-main">
                <section class="asat-public-home-hero">
                    <div class="container px-4">
                        <div class="asat-public-intro">
                            <p class="asat-public-kicker">Automated Scholarship Platform</p>
                            <h1>Automated Scholarship Application Tracking and Notification System</h1>
                            <p>
                                ASATNSS gives students a secure place to apply for scholarships, track application status,
                                receive notifications, and connect external Tally submissions into one admin review workflow.
                            </p>

                            <div class="asat-public-actions">
                                @auth
                                    <a href="{{ $workspaceRoute }}" class="asat-button asat-button-light">Open Dashboard</a>
                                    @if ($user->is_admin)
                                        <a href="{{ route('admin.applications.index') }}" class="asat-button asat-button-outline-light">Review Applications</a>
                                    @else
                                        <a href="{{ route('applications.index') }}" class="asat-button asat-button-outline-light">Applications</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="asat-button asat-button-light">Login</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="asat-button asat-button-outline-light">Register</a>
                                    @endif
                                    @if (! blank($tallyFormUrl))
                                        <a href="{{ $tallyFormUrl }}" target="_blank" rel="noopener noreferrer" class="asat-button asat-button-outline-light">Apply Externally</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="asat-public-home-footer">
                <div class="container px-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3">
                        <p class="mb-0">ASATNSS scholarship portal</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('login') }}">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">Register</a>
                            @endif
                            @if (! blank($tallyFormUrl))
                                <a href="{{ $tallyFormUrl }}" target="_blank" rel="noopener noreferrer">Apply Externally</a>
                            @endif
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
