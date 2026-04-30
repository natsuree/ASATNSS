<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ __('ASATNSS Scholarship Portal') }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ __('Submit and track scholarship applications from your student dashboard.') }}</p>
                        </div>

                        <a href="{{ route('applications.index') }}" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                            {{ __('View applications') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
