<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="asat-page-kicker">{{ auth()->user()->is_admin ? __('Admin Workspace') : __('Student Workspace') }}</p>
            <h2 class="asat-page-title">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    @php
        $user = auth()->user();
        $isAdmin = $user->is_admin;

        if ($isAdmin) {
            $totalApplications = \App\Models\Application::count();
            $pendingApplications = \App\Models\Application::where('status', 'Pending')->count();
            $approvedApplications = \App\Models\Application::where('status', 'Approved')->count();
            $rejectedApplications = \App\Models\Application::where('status', 'Rejected')->count();
            $recentApplications = \App\Models\Application::latest()->limit(4)->get();
        } else {
            $totalApplications = $user->applications()->count();
            $pendingApplications = $user->applications()->where('status', 'Pending')->count();
            $approvedApplications = $user->applications()->where('status', 'Approved')->count();
            $recentApplications = $user->applications()->latest()->limit(4)->get();
            $recentNotifications = $user->notifications()->latest()->limit(4)->get();
        }
    @endphp

    <div class="asat-section">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="asat-hero p-6 sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm font-black uppercase text-white/75">
                            {{ $isAdmin ? __('Scholarship review center') : __('Scholarship account center') }}
                        </p>
                        <h3 class="asat-hero-title mt-3">
                            {{ $isAdmin ? __('Review applications and monitor system activity.') : __('Track your scholarship application progress.') }}
                        </h3>
                        <p class="asat-hero-copy mt-4">
                            {{ $isAdmin ? __('Manage approvals, applicant records, notifications, and admin users from one dashboard.') : __('Submit applications, view status updates, and keep your profile ready for review.') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        @if ($isAdmin)
                            <a href="{{ route('admin.applications.index') }}" class="asat-button bg-white text-[var(--asat-navy)]">
                                Review applications
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="asat-button border border-white/30 text-white hover:bg-white/10">
                                Admin users
                            </a>
                        @else
                            <a href="{{ route('applications.create') }}" class="asat-button bg-white text-[var(--asat-navy)]">
                                New application
                            </a>
                            <a href="{{ route('applications.index') }}" class="asat-button border border-white/30 text-white hover:bg-white/10">
                                My applications
                            </a>
                        @endif
                    </div>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-3 {{ $isAdmin ? 'lg:grid-cols-4' : '' }}">
                <div class="asat-stat p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-black text-slate-500">{{ $isAdmin ? __('Total Applications') : __('My Applications') }}</p>
                            <p class="mt-2 text-3xl font-black text-[var(--asat-ink)]">{{ $totalApplications }}</p>
                        </div>
                        <span class="asat-stat-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="asat-stat is-gold p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-black text-slate-500">{{ __('Pending') }}</p>
                            <p class="mt-2 text-3xl font-black text-[var(--asat-ink)]">{{ $pendingApplications }}</p>
                        </div>
                        <span class="asat-stat-icon bg-yellow-100 text-[var(--asat-gold)]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="asat-stat is-sky p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-black text-slate-500">{{ __('Approved') }}</p>
                            <p class="mt-2 text-3xl font-black text-[var(--asat-ink)]">{{ $approvedApplications }}</p>
                        </div>
                        <span class="asat-stat-icon bg-sky-100 text-[var(--asat-sky)]">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                </div>

                @if ($isAdmin)
                    <div class="asat-stat is-red p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-black text-slate-500">{{ __('Rejected') }}</p>
                                <p class="mt-2 text-3xl font-black text-[var(--asat-ink)]">{{ $rejectedApplications }}</p>
                            </div>
                            <span class="asat-stat-icon bg-red-100 text-[var(--asat-red)]">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </div>
                    </div>
                @endif
            </section>

            <section class="grid gap-6 lg:grid-cols-[1.1fr_.9fr]">
                <div class="asat-card">
                    <div class="asat-card-header flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-black text-[var(--asat-ink)]">{{ __('Recent Applications') }}</h3>
                            <p class="mt-1 text-sm text-slate-600">{{ $isAdmin ? __('Latest records in the review queue.') : __('Your latest scholarship submissions.') }}</p>
                        </div>
                        <a href="{{ $isAdmin ? route('admin.applications.index') : route('applications.index') }}" class="text-sm font-black text-[var(--asat-teal)] hover:text-[var(--asat-navy)]">
                            {{ __('View all') }}
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentApplications as $application)
                            <div class="grid gap-3 p-5 sm:grid-cols-[1fr_auto] sm:items-center">
                                <div>
                                    <p class="font-black text-slate-900">{{ $application->full_name }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ $application->scholarship_type }} · {{ $application->course }}</p>
                                </div>
                                <span class="asat-badge {{ $application->status === 'Approved' ? 'asat-badge-approved' : ($application->status === 'Rejected' ? 'asat-badge-rejected' : 'asat-badge-pending') }}">
                                    {{ $application->status }}
                                </span>
                            </div>
                        @empty
                            <div class="p-5">
                                <div class="asat-empty">
                                    <p class="font-black text-slate-900">{{ __('No applications yet') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="asat-card">
                    <div class="asat-card-header">
                        <h3 class="text-lg font-black text-[var(--asat-ink)]">{{ $isAdmin ? __('System Modules') : __('Recent Notifications') }}</h3>
                    </div>

                    @if ($isAdmin)
                        <div class="space-y-3 p-5">
                            <div class="asat-module-row">
                                <p class="font-black text-slate-900">{{ __('Application Review') }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ __('Pending, approved, and rejected records.') }}</p>
                            </div>
                            <div class="asat-module-row">
                                <p class="font-black text-slate-900">{{ __('Tally Intake') }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ __('External form submissions enter the same queue.') }}</p>
                            </div>
                            <div class="asat-module-row">
                                <p class="font-black text-slate-900">{{ __('Email Reputation') }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ __('Abstract API validation runs server-side.') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="divide-y divide-slate-100">
                            @forelse ($recentNotifications as $notification)
                                <div class="asat-feed-item {{ $notification->is_read ? '' : 'is-unread' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-black text-slate-900">{{ $notification->title }}</p>
                                            <p class="mt-1 text-sm text-slate-600">{{ $notification->message }}</p>
                                        </div>
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-600">{{ $notification->status }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-5">
                                    <div class="asat-empty">
                                        <p class="font-black text-slate-900">{{ __('No notifications yet') }}</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
