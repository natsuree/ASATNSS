<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="asat-page-kicker">{{ __('Student Applications') }}</p>
            <h2 class="asat-page-title">
                {{ __('Scholarship Application') }}
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
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-black uppercase text-white/75">{{ __('Application Center') }}</p>
                        <h3 class="asat-hero-title mt-3">{{ __('Submit and monitor scholarship requests.') }}</h3>
                    </div>

                    <a href="{{ route('applications.create') }}" class="asat-button bg-white text-[var(--asat-navy)]">
                        {{ __('New application') }}
                    </a>
                </div>
            </section>

            <div class="asat-card">
                <div class="asat-card-header">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-black text-[var(--asat-ink)]">{{ __('Application history') }}</h3>
                            <p class="mt-1 text-sm text-slate-600">{{ __('All submitted scholarship applications.') }}</p>
                        </div>

                        <a href="{{ route('applications.create') }}" class="asat-button asat-button-primary">
                            {{ __('New application') }}
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="asat-table min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider">{{ __('Scholarship') }}</th>
                                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider">{{ __('Student') }}</th>
                                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-4 py-3 text-right text-xs uppercase tracking-wider">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($applications as $application)
                                    <tr>
                                        <td class="px-4 py-4">
                                            <div class="font-black text-slate-900">{{ $application->scholarship_type }}</div>
                                            <div class="text-sm text-slate-500">{{ $application->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-700">{{ $application->student_id }} - {{ $application->course }}</td>
                                        <td class="px-4 py-4 text-sm text-slate-700">
                                            <span class="asat-badge {{ $application->status === 'Approved' ? 'asat-badge-approved' : ($application->status === 'Rejected' ? 'asat-badge-rejected' : 'asat-badge-pending') }}">
                                                {{ $application->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex justify-end gap-3">
                                                <a href="{{ route('applications.show', $application) }}" class="text-sm font-black text-[var(--asat-navy)] hover:text-[var(--asat-teal)]">{{ __('View') }}</a>
                                                @if ($application->status === 'Pending')
                                                    <form method="POST" action="{{ route('applications.destroy', $application) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm font-black text-red-700 hover:text-red-900">
                                                            {{ __('Withdraw') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8">
                                            <div class="asat-empty">
                                                <p class="font-black text-slate-900">{{ __('No applications yet.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>

            <div class="asat-card">
                <div class="asat-card-header">
                    <h3 class="text-lg font-black text-[var(--asat-ink)]">{{ __('Notifications') }}</h3>
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
