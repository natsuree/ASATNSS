<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scholarship Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ __(str_replace('-', ' ', session('status'))) }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('Application history') }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ __('Submit applications and track review statuses from one place.') }}</p>
                        </div>

                        <a href="{{ route('applications.create') }}" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                            {{ __('New application') }}
                        </a>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Scholarship') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Student') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Status') }}</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($applications as $application)
                                    <tr>
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900">{{ $application->scholarship_type }}</div>
                                            <div class="text-sm text-gray-500">{{ $application->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $application->student_id }} · {{ $application->course }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-700">{{ $application->status }}</td>
                                        <td class="px-4 py-4">
                                            <div class="flex justify-end gap-3">
                                                <a href="{{ route('applications.show', $application) }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">{{ __('View') }}</a>
                                                @if ($application->status === 'Pending')
                                                    <form method="POST" action="{{ route('applications.destroy', $application) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm font-medium text-red-700 hover:text-red-900">
                                                            {{ __('Withdraw') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-600">{{ __('No applications yet.') }}</td>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Notifications') }}</h3>

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
