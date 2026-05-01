<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="asat-page-kicker">{{ __('Application Record') }}</p>
            <h2 class="asat-page-title">
                {{ __('Application Details') }}
            </h2>
        </div>
    </x-slot>

    <div class="asat-section">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="asat-card">
                <div class="p-6 space-y-6">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-5">
                        <div>
                            <p class="text-sm font-black text-slate-500">{{ __('Status') }}</p>
                            <span class="mt-2 asat-badge {{ $application->status === 'Approved' ? 'asat-badge-approved' : ($application->status === 'Rejected' ? 'asat-badge-rejected' : 'asat-badge-pending') }}">
                                {{ $application->status }}
                            </span>
                        </div>

                        <a href="{{ route('applications.index') }}" class="text-sm font-black text-[var(--asat-navy)] hover:text-[var(--asat-teal)]">{{ __('Back') }}</a>
                    </div>

                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div class="asat-module-row">
                            <dt class="text-sm font-black text-slate-500">{{ __('Full name') }}</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $application->full_name }}</dd>
                        </div>
                        <div class="asat-module-row">
                            <dt class="text-sm font-black text-slate-500">{{ __('Email') }}</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $application->email }}</dd>
                        </div>
                        <div class="asat-module-row">
                            <dt class="text-sm font-black text-slate-500">{{ __('Student ID') }}</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $application->student_id }}</dd>
                        </div>
                        <div class="asat-module-row">
                            <dt class="text-sm font-black text-slate-500">{{ __('Course and year') }}</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $application->course }} - {{ $application->year_level }}</dd>
                        </div>
                        <div class="asat-module-row sm:col-span-2">
                            <dt class="text-sm font-black text-slate-500">{{ __('Scholarship type') }}</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $application->scholarship_type }}</dd>
                        </div>
                        <div class="asat-module-row sm:col-span-2">
                            <dt class="text-sm font-black text-slate-500">{{ __('Reason') }}</dt>
                            <dd class="mt-1 whitespace-pre-line font-semibold text-slate-900">{{ $application->reason_for_applying ?: __('No reason provided.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
