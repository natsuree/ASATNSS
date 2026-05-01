<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="asat-page-kicker">{{ __('Access Control') }}</p>
            <h2 class="asat-page-title">
                {{ __('Admin Users') }}
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
                        <p class="text-sm font-black uppercase text-white/75">{{ __('Admin role management') }}</p>
                        <h3 class="asat-hero-title mt-3">{{ __('Promote trusted users for review access.') }}</h3>
                    </div>

                    <a href="{{ route('admin.applications.index') }}" class="asat-button bg-white text-[var(--asat-navy)]">
                        Applications
                    </a>
                </div>
            </section>

            <div class="asat-card">
                <div class="asat-card-header">
                    <h3 class="text-lg font-black text-[var(--asat-ink)]">Role management</h3>
                    <p class="mt-1 text-sm text-slate-600">Promote registered users to admin, adjust roles, or safely remove accounts when needed.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="asat-table min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs uppercase tracking-wider">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs uppercase tracking-wider">{{ __('Email') }}</th>
                                <th class="px-6 py-3 text-left text-xs uppercase tracking-wider">{{ __('Current role') }}</th>
                                <th class="px-6 py-3 text-right text-xs uppercase tracking-wider">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 font-black text-slate-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        <span class="asat-badge {{ $user->is_admin ? 'asat-badge-approved' : 'asat-badge-pending' }}">
                                            {{ $user->is_admin ? __('Admin') : __('User') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-wrap justify-end gap-2">
                                        @if (! $user->is_admin)
                                            <form method="POST" action="{{ route('admin.users.promote', $user) }}" class="inline-flex">
                                                @csrf
                                                @method('PATCH')
                                                <x-primary-button>{{ __('Promote') }}</x-primary-button>
                                            </form>
                                        @elseif (! auth()->user()->is($user))
                                            <form method="POST" action="{{ route('admin.users.demote', $user) }}" class="inline-flex">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="asat-button asat-button-danger">
                                                    {{ __('Demote') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-sm font-black text-slate-500">{{ __('Current admin') }}</span>
                                        @endif

                                        @if (! auth()->user()->is($user))
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-flex" onsubmit="return confirm('Remove this user and their related records? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="asat-button asat-button-danger">
                                                    {{ __('Remove') }}
                                                </button>
                                            </form>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8">
                                        <div class="asat-empty">
                                            <p class="font-black text-slate-900">{{ __('No users found.') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
