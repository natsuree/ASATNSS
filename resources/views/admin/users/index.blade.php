<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Users') }}
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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Email') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Current role') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->is_admin ? __('Admin') : __('User') }}</td>
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
                                            <span class="text-sm text-gray-500">{{ __('Current admin') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-600">{{ __('No users found.') }}</td>
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
