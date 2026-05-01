<x-app-layout>
    <x-slot name="header">
        <h2 class="asat-page-title">
            {{ __('Admin Applications') }}
        </h2>
    </x-slot>

    <div class="asat-section">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ __(str_replace('-', ' ', session('status'))) }}
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="asat-stat p-6">
                    <p class="text-sm font-bold uppercase tracking-wider text-slate-500">{{ __('Pending') }}</p>
                    <p class="mt-2 text-3xl font-extrabold text-[var(--asat-ink)]">{{ $pendingCount }}</p>
                </div>
                <div class="asat-stat p-6">
                    <p class="text-sm font-bold uppercase tracking-wider text-slate-500">{{ __('Approved') }}</p>
                    <p class="mt-2 text-3xl font-extrabold text-[var(--asat-ink)]">{{ $approvedCount }}</p>
                </div>
                <div class="asat-stat is-red p-6">
                    <p class="text-sm font-bold uppercase tracking-wider text-slate-500">{{ __('Rejected') }}</p>
                    <p class="mt-2 text-3xl font-extrabold text-[var(--asat-ink)]">{{ $rejectedCount }}</p>
                </div>
            </div>

            <div class="asat-card">
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-extrabold text-[var(--asat-ink)]">Scholarship application review</h3>
                    <p class="mt-1 text-sm text-slate-600">Pending submissions appear here for approval or rejection.</p>
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
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $application->full_name }}</div>
                                        <div class="text-sm text-slate-500">{{ $application->student_id }}</div>
                                        <div class="text-xs text-slate-500">{{ $application->email }}</div>
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
                                                    <button type="submit" class="inline-flex items-center rounded-md border border-red-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-red-700 transition hover:bg-red-50">
                                                        {{ __('Reject') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-sm font-bold text-slate-500">{{ __('Reviewed') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-600">{{ __('No applications yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="asat-card">
                <div class="p-6">
                    <h3 class="text-lg font-extrabold text-[var(--asat-ink)]">{{ __('Recent notifications') }}</h3>

                    <div class="mt-4 divide-y divide-gray-100">
                        @forelse ($notifications as $notification)
                            <div class="py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-bold text-slate-900">{{ $notification->title }}</p>
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">{{ $notification->status }}</span>
                                </div>
                                <p class="mt-1 text-sm text-slate-600">{{ $notification->message }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-600">{{ __('No notifications yet.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
