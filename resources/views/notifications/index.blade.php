<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="asat-page-kicker">{{ __('Status Center') }}</p>
            <h2 class="asat-page-title">
                {{ __('Notifications') }}
            </h2>
        </div>
    </x-slot>

    <div class="asat-section">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="asat-card">
                <div class="asat-card-header">
                    <h3 class="text-lg font-black text-[var(--asat-ink)]">{{ __('Notification Feed') }}</h3>
                    <p class="mt-1 text-sm text-slate-600">{{ __('Application updates and system alerts.') }}</p>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse ($notifications as $notification)
                        <div class="asat-feed-item {{ $notification->is_read ? '' : 'is-unread' }}">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-black text-slate-900">{{ $notification->title }}</h3>
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-600">{{ $notification->status }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-600">{{ $notification->message }}</p>
                                    <p class="mt-2 text-xs text-slate-500">{{ $notification->created_at->format('M d, Y h:i A') }}</p>
                                </div>

                                @unless ($notification->is_read)
                                    <form method="POST" action="{{ route('notifications.update', $notification) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm font-black text-[var(--asat-navy)] hover:text-[var(--asat-teal)]">
                                            {{ __('Mark as read') }}
                                        </button>
                                    </form>
                                @endunless
                            </div>
                        </div>
                    @empty
                        <div class="p-6">
                            <div class="asat-empty">
                                <p class="font-black text-slate-900">{{ __('No notifications yet.') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
