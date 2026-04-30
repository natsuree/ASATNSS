<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="divide-y divide-gray-100">
                    @forelse ($notifications as $notification)
                        <div class="p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold text-gray-900">{{ $notification->title }}</h3>
                                        <span class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-600">{{ $notification->status }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">{{ $notification->message }}</p>
                                    <p class="mt-2 text-xs text-gray-500">{{ $notification->created_at->format('M d, Y h:i A') }}</p>
                                </div>

                                @unless ($notification->is_read)
                                    <form method="POST" action="{{ route('notifications.update', $notification) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                                            {{ __('Mark as read') }}
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-sm text-gray-600">{{ __('No notifications yet.') }}</div>
                    @endforelse
                </div>

                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
