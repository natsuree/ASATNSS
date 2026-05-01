<x-app-layout>
    <x-slot name="header">
        <h2 class="asat-page-title">
            {{ __('Scholarship Application') }}
        </h2>
    </x-slot>

    <div class="asat-section">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ __(str_replace('-', ' ', session('status'))) }}
                </div>
            @endif

            <div class="asat-card">
                <div class="p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-extrabold text-[var(--asat-ink)]">{{ __('Application history') }}</h3>
                            <p class="mt-1 text-sm text-slate-600">{{ __('Submit applications and track review statuses from one place.') }}</p>
                        </div>

                        <a href="{{ route('applications.create') }}" class="asat-action-primary inline-flex items-center rounded-md px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition">
                            {{ __('New application') }}
                        </a>
                    </div>

                    <div class="mt-6 overflow-x-auto">
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
                                            <div class="font-bold text-slate-900">{{ $application->scholarship_type }}</div>
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
                                                <a href="{{ route('applications.show', $application) }}" class="text-sm font-bold text-[var(--asat-navy)] hover:text-[var(--asat-teal)]">{{ __('View') }}</a>
                                                @if ($application->status === 'Pending')
                                                    <form method="POST" action="{{ route('applications.destroy', $application) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm font-bold text-red-700 hover:text-red-900">
                                                            {{ __('Withdraw') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-600">{{ __('No applications yet.') }}</td>
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
                <div class="p-6">
                    <h3 class="text-lg font-extrabold text-[var(--asat-ink)]">{{ __('Notifications') }}</h3>

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
