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

        <div class="asat-landing-shell">
            <header class="asat-landing-nav">
                <div class="container-fluid px-4 px-lg-5">
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <a href="{{ url('/') }}" class="d-flex align-items-center gap-3 text-decoration-none">
                            <span class="asat-brand-mark">A</span>
                            <span>
                                <span class="d-block asat-brand-title">ASATNSS</span>
                                <span class="d-block asat-brand-subtitle">Scholarship Management Portal</span>
                            </span>
                        </a>

                        <nav class="asat-landing-menu d-none d-lg-flex align-items-center gap-3">
                            @auth
                                <a href="{{ $workspaceRoute }}" class="asat-landing-link">Dashboard</a>
                                <a href="{{ route('applications.index') }}" class="asat-landing-link">Applications</a>
                                @if ($user->is_admin)
                                    <a href="{{ route('admin.applications.index') }}" class="asat-landing-link">Admin</a>
                                @endif
                            @else
                                <a href="{{ url('/') }}" class="asat-landing-link">Home</a>
                                <a href="{{ route('login') }}" class="asat-landing-link">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="asat-landing-link">Register</a>
                                @endif
                            @endauth
                        </nav>

                        <div class="d-flex align-items-center gap-2">
                            @auth
                                <a href="{{ $workspaceRoute }}" class="asat-button asat-button-primary">Open Workspace</a>
                            @else
                                <a href="{{ route('login') }}" class="asat-button asat-button-secondary d-none d-sm-inline-flex">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="asat-button asat-button-primary">Register</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </header>

            <main>
                <section class="asat-landing-hero">
                    <div class="container-fluid px-4 px-lg-5">
                        <div class="row align-items-center g-5">
                            <div class="col-xl-6 col-lg-6">
                                <div class="asat-landing-copy">
                                    <p class="asat-landing-kicker">Automated Scholarship Platform</p>
                                    <h1 class="asat-landing-title">Automated Scholarship Application Tracking and Notification System</h1>
                                    <p class="asat-landing-description">
                                        ASATNSS is a complete scholarship portal for student applications, real-time status tracking,
                                        Tally-powered external intake, admin approval workflows, and secure email reputation validation.
                                    </p>

                                    <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                                        @auth
                                            <a href="{{ $workspaceRoute }}" class="asat-button asat-button-primary">Go to Dashboard</a>
                                            <a href="{{ route('applications.index') }}" class="asat-button asat-button-secondary">View Applications</a>
                                        @else
                                            <a href="{{ route('login') }}" class="asat-button asat-button-primary">Login</a>
                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}" class="asat-button asat-button-secondary">Register</a>
                                            @endif
                                            @if (! blank($tallyFormUrl))
                                                <a href="{{ $tallyFormUrl }}" target="_blank" rel="noopener noreferrer" class="asat-button asat-button-secondary">Apply Externally</a>
                                            @endif
                                        @endauth
                                    </div>

                                    <div class="asat-landing-proof mt-4">
                                        <span>Laravel</span>
                                        <span>Bootstrap + Blade</span>
                                        <span>Abstract API</span>
                                        <span>Tally Webhook</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6">
                                <div class="asat-landing-visual">
                                    <div class="asat-landing-visual-head">
                                        <div>
                                            <p class="asat-landing-visual-kicker">System Preview</p>
                                            <h2 class="asat-landing-visual-title">Scholarship Operations Board</h2>
                                        </div>
                                        <span class="asat-badge asat-badge-approved">Active</span>
                                    </div>

                                    <div class="asat-landing-flow">
                                        <div class="asat-landing-flow-step">
                                            <strong>01</strong>
                                            <span>Student application</span>
                                        </div>
                                        <div class="asat-landing-flow-step">
                                            <strong>02</strong>
                                            <span>Email validation</span>
                                        </div>
                                        <div class="asat-landing-flow-step">
                                            <strong>03</strong>
                                            <span>Admin review</span>
                                        </div>
                                    </div>

                                    <div class="asat-landing-preview">
                                        <div class="asat-landing-preview-row">
                                            <div>
                                                <p class="asat-landing-preview-label">Applicant Queue</p>
                                                <h3>Academic Scholarship</h3>
                                            </div>
                                            <span class="asat-badge asat-badge-pending">Pending</span>
                                        </div>

                                        <div class="row g-3 mt-1">
                                            <div class="col-md-6">
                                                <div class="asat-landing-mini">
                                                    <p>Internal Application</p>
                                                    <span>Student dashboard submission</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="asat-landing-mini">
                                                    <p>External Tally Intake</p>
                                                    <span>Webhook application capture</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="asat-landing-mini">
                                                    <p>Email Reputation</p>
                                                    <span>Server-side applicant validation</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="asat-landing-mini">
                                                    <p>Notifications</p>
                                                    <span>Status updates for students</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="features" class="asat-landing-section">
                    <div class="container-fluid px-4 px-lg-5">
                        <div class="asat-landing-section-head">
                            <p class="asat-page-kicker">Key Features</p>
                            <h2>Everything needed for a scholarship workflow</h2>
                            <p>The public homepage explains the complete platform while the protected system continues to handle applications, approvals, and integrations.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-xl-4 col-md-6">
                                <article class="asat-landing-feature">
                                    <h3>Online Scholarship Application</h3>
                                    <p>Students can register, log in, manage their profile, and submit scholarship applications in one portal.</p>
                                </article>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <article class="asat-landing-feature">
                                    <h3>Real-Time Tracking</h3>
                                    <p>Application statuses remain visible to students from submission through approval or rejection.</p>
                                </article>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <article class="asat-landing-feature">
                                    <h3>External Tally Applications</h3>
                                    <p>Third-party form submissions can enter the same application pipeline through the Tally webhook system.</p>
                                </article>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <article class="asat-landing-feature">
                                    <h3>Admin Approval System</h3>
                                    <p>Authorized admins can review all records, approve or reject applicants, and manage users.</p>
                                </article>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <article class="asat-landing-feature">
                                    <h3>Notifications</h3>
                                    <p>Students receive updates when applications are submitted, reviewed, or changed by administrators.</p>
                                </article>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <article class="asat-landing-feature">
                                    <h3>Secure API Validation</h3>
                                    <p>Abstract Email Reputation validation helps reduce suspicious or low-quality submissions before review.</p>
                                </article>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="about" class="asat-landing-section asat-landing-section-alt">
                    <div class="container-fluid px-4 px-lg-5">
                        <div class="row g-4 align-items-stretch">
                            <div class="col-xl-6">
                                <div class="asat-landing-story">
                                    <p class="asat-page-kicker">About ASATNSS</p>
                                    <h2>Built to make scholarship processing visible and reliable</h2>
                                    <p>
                                        ASATNSS combines a public-facing homepage, a student portal, an admin review workspace,
                                        and API/webhook integrations into one academic system. It is designed for final project presentation,
                                        but structured like a real scholarship operations platform.
                                    </p>
                                    <p>
                                        Internal applications and Tally submissions coexist in the same backend workflow, while authenticated
                                        students and admins continue to operate under separate protected routes and role rules.
                                    </p>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <div class="asat-landing-stat">
                                            <span>{{ $stats['applications'] }}</span>
                                            <p>Total Applications</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="asat-landing-stat">
                                            <span>{{ $stats['pending'] }}</span>
                                            <p>Pending Review</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="asat-landing-stat">
                                            <span>{{ $stats['approved'] }}</span>
                                            <p>Approved</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="asat-landing-architecture">
                                            <h3>Platform Coverage</h3>
                                            <div class="asat-landing-architecture-grid">
                                                <span>Student Portal</span>
                                                <span>Admin Dashboard</span>
                                                <span>Webhook Intake</span>
                                                <span>API Validation</span>
                                                <span>Notifications</span>
                                                <span>Role Protection</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="faq" class="asat-landing-section">
                    <div class="container-fluid px-4 px-lg-5">
                        <div class="asat-landing-section-head">
                            <p class="asat-page-kicker">FAQ</p>
                            <h2>Common questions</h2>
                            <p>Quick answers for visitors before they log in to the internal scholarship system.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-4">
                                <div class="asat-landing-faq">
                                    <h3>Who uses ASATNSS?</h3>
                                    <p>Students use it to submit and track applications, while admins use it to review and manage scholarship records.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="asat-landing-faq">
                                    <h3>Can external forms be accepted?</h3>
                                    <p>Yes. Tally submissions can be received through the webhook route and saved into the same application workflow.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="asat-landing-faq">
                                    <h3>How is submission quality checked?</h3>
                                    <p>ASATNSS uses server-side email reputation validation to help reject suspicious or disposable email submissions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="asat-landing-footer">
                <div class="container-fluid px-4 px-lg-5">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-6">
                            <p class="mb-1 fw-black">Automated Scholarship Application Tracking and Notification System</p>
                            <p class="mb-0 text-muted">A scholarship-focused Laravel platform with protected student and admin workflows.</p>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex flex-wrap justify-content-lg-end gap-3">
                                <a href="{{ url('/') }}" class="asat-landing-footer-link">Home</a>
                                <a href="{{ route('login') }}" class="asat-landing-footer-link">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="asat-landing-footer-link">Register</a>
                                @endif
                                @if (! blank($tallyFormUrl))
                                    <a href="{{ $tallyFormUrl }}" target="_blank" rel="noopener noreferrer" class="asat-landing-footer-link">Apply Externally</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
