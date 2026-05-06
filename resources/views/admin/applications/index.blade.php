<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="asat-page-kicker">{{ __('Admin Review') }}</p>
            <h2 class="asat-page-title">
                {{ __('Admin Applications') }}
            </h2>
        </div>
    </x-slot>

    <div class="asat-section">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="asat-alert-success p-4 text-sm font-semibold">
                    {{ __(str_replace('-', ' ', session('status'))) }}
                </div>
            @endif

            <section class="asat-hero p-6 sm:p-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm font-black uppercase text-white/75">{{ __('Scholarship Approval Board') }}</p>
                        <h3 class="asat-hero-title mt-3">{{ __('Review pending student applications.') }}</h3>
                    </div>

                    <a href="{{ route('admin.users.index') }}" class="asat-button bg-white text-[var(--asat-navy)]">
                        {{ __('Manage users') }}
                    </a>
                </div>
            </section>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="asat-stat p-6">
                    <p class="text-sm font-black text-slate-500">{{ __('Pending') }}</p>
                    <p class="mt-2 text-3xl font-black text-[var(--asat-ink)]">{{ $pendingCount }}</p>
                </div>
                <div class="asat-stat is-sky p-6">
                    <p class="text-sm font-black text-slate-500">{{ __('Approved') }}</p>
                    <p class="mt-2 text-3xl font-black text-[var(--asat-ink)]">{{ $approvedCount }}</p>
                </div>
                <div class="asat-stat is-red p-6">
                    <p class="text-sm font-black text-slate-500">{{ __('Rejected') }}</p>
                    <p class="mt-2 text-3xl font-black text-[var(--asat-ink)]">{{ $rejectedCount }}</p>
                </div>
            </div>

            <div class="asat-card">
                <div class="asat-card-header">
                    <h3 class="text-lg font-black text-[var(--asat-ink)]">Scholarship application review</h3>
                    <p class="mt-1 text-sm text-slate-600">Internal and Tally submissions appear in this queue.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="asat-table min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs uppercase tracking-wider">{{ __('Applicant') }}</th>
                                <th class="px-6 py-3 text-left text-xs uppercase tracking-wider">{{ __('Program') }}</th>
                                <th class="px-6 py-3 text-left text-xs uppercase tracking-wider">{{ __('Scholarship') }}</th>
                                <th class="px-6 py-3 text-left text-xs uppercase tracking-wider">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-right text-xs uppercase tracking-wider">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($applications as $application)
                                <tr class="align-top">
                                    <td class="px-6 py-4">
                                        <div class="font-black text-slate-900">{{ $application->full_name }}</div>
                                        <div class="text-sm text-slate-500">{{ __('Student ID') }}: {{ $application->student_id }}</div>
                                        <div class="text-xs text-slate-500">{{ $application->email }}</div>
                                        <div class="mt-2">
                                            <span class="asat-badge {{ $application->tally_submission_id ? 'asat-badge-approved' : 'asat-badge-pending' }}">
                                                {{ $application->tally_submission_id ? __('Tally submission') : __('Portal submission') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $application->course }} - {{ $application->year_level }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $application->scholarship_type }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        <span class="asat-badge {{ $application->status === 'Approved' ? 'asat-badge-approved' : ($application->status === 'Rejected' ? 'asat-badge-rejected' : 'asat-badge-pending') }}">
                                            {{ $application->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($application->status === 'Pending')
                                            <div class="inline-flex items-center gap-2">
                                                <form method="POST" action="{{ route('admin.applications.update', $application) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Approved">
                                                    <x-primary-button>{{ __('Approve') }}</x-primary-button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.applications.update', $application) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Rejected">
                                                    <button type="submit" class="asat-button asat-button-danger">
                                                        {{ __('Reject') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-sm font-black text-slate-500">{{ __('Reviewed') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-slate-50/70">
                                    <td colspan="5" class="px-6 py-5">
                                        <div class="grid gap-4 lg:grid-cols-[minmax(0,.9fr)_minmax(0,1.1fr)]">
                                            <section class="border-l-4 border-[var(--asat-teal)] bg-white p-4">
                                                <h4 class="text-sm font-black uppercase tracking-wider text-slate-500">{{ __('Applicant Information') }}</h4>
                                                <dl class="mt-3 grid gap-3 sm:grid-cols-2">
                                                    <div>
                                                        <dt class="text-xs font-black uppercase tracking-wider text-slate-500">{{ __('Full Name') }}</dt>
                                                        <dd class="mt-1 break-words text-sm font-semibold text-slate-900">{{ $application->full_name }}</dd>
                                                    </div>
                                                    <div>
                                                        <dt class="text-xs font-black uppercase tracking-wider text-slate-500">{{ __('Student ID') }}</dt>
                                                        <dd class="mt-1 break-words text-sm font-semibold text-slate-900">{{ $application->student_id }}</dd>
                                                    </div>
                                                    <div>
                                                        <dt class="text-xs font-black uppercase tracking-wider text-slate-500">{{ __('Email') }}</dt>
                                                        <dd class="mt-1 break-words text-sm font-semibold text-slate-900">{{ $application->email }}</dd>
                                                    </div>
                                                    <div>
                                                        <dt class="text-xs font-black uppercase tracking-wider text-slate-500">{{ __('Submitted Through') }}</dt>
                                                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $application->tally_submission_id ? __('Tally') : __('Portal') }}</dd>
                                                    </div>
                                                </dl>
                                            </section>

                                            <section class="border-l-4 border-[var(--asat-gold)] bg-white p-4">
                                                <h4 class="text-sm font-black uppercase tracking-wider text-slate-500">{{ __('Reason for Applying') }}</h4>
                                                <p class="mt-3 whitespace-pre-line break-words text-sm leading-6 text-slate-700">{{ filled($application->reason_for_applying) ? $application->reason_for_applying : __('No reason provided.') }}</p>
                                            </section>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8">
                                        <div class="asat-empty">
                                            <p class="font-black text-slate-900">{{ __('No applications yet.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="asat-card">
                <div class="asat-card-header">
                    <h3 class="text-lg font-black text-[var(--asat-ink)]">{{ __('Recent notifications') }}</h3>
                </div>

                <div class="divide-y divide-gray-100">
                        @forelse ($notifications as $notification)
                            <div class="asat-feed-item {{ $notification->is_read ? '' : 'is-unread' }}">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-black text-slate-900">{{ $notification->title }}</p>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-600">{{ $notification->status }}</span>
                                </div>
                                <p class="mt-1 text-sm text-slate-600">{{ $notification->message }}</p>
                            </div>
                        @empty
                            <div class="p-6">
                                <div class="asat-empty">
                                    <p class="font-black text-slate-900">{{ __('No notifications yet.') }}</p>
                                </div>
                            </div>
                        @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
