<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ __(str_replace('-', ' ', session('status'))) }}
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-sm font-medium text-gray-500">{{ __('Pending') }}</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $pendingCount }}</p>
                </div>
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-sm font-medium text-gray-500">{{ __('Approved') }}</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $approvedCount }}</p>
                </div>
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <p class="text-sm font-medium text-gray-500">{{ __('Rejected') }}</p>
                    <p class="mt-2 text-3xl font-semibold text-gray-900">{{ $rejectedCount }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Applicant') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Program') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Scholarship') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($applications as $application)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $application->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->student_id }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $application->course }} - {{ $application->year_level }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $application->scholarship_type }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $application->status }}</td>
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
                                            <span class="text-sm text-gray-500">{{ __('Reviewed') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-600">{{ __('No applications yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Recent notifications') }}</h3>

                    <div class="mt-4 divide-y divide-gray-100">
                        @forelse ($notifications as $notification)
                            <div class="py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-medium text-gray-900">{{ $notification->title }}</p>
                                    <span class="text-xs text-gray-500">{{ $notification->status }}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ $notification->message }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-600">{{ __('No notifications yet.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
