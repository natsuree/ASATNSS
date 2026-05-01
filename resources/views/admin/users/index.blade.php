<x-app-layout>
    <x-slot name="header">
        <h2 class="asat-page-title">
            {{ __('Admin Users') }}
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
                <div class="border-b border-slate-200 px-6 py-5">
                    <h3 class="text-lg font-extrabold text-[var(--asat-ink)]">Role management</h3>
                    <p class="mt-1 text-sm text-slate-600">Promote registered users to admin or return admins to normal access.</p>
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
                                    <td class="px-6 py-4 font-bold text-slate-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        <span class="asat-badge {{ $user->is_admin ? 'asat-badge-approved' : 'asat-badge-pending' }}">
                                            {{ $user->is_admin ? __('Admin') : __('User') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
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
                                                <button type="submit" class="inline-flex items-center rounded-md border border-red-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-red-700 transition hover:bg-red-50">
                                                    {{ __('Demote') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-sm font-bold text-slate-500">{{ __('Current admin') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-600">{{ __('No users found.') }}</td>
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
