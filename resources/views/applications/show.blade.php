<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">{{ __('Status') }}</p>
                            <h3 class="mt-1 text-2xl font-semibold text-gray-900">{{ $application->status }}</h3>
                        </div>

                        <a href="{{ route('applications.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Back') }}</a>
                    </div>

                    <dl class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Full name') }}</dt>
                            <dd class="mt-1 text-gray-900">{{ $application->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                            <dd class="mt-1 text-gray-900">{{ $application->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Student ID') }}</dt>
                            <dd class="mt-1 text-gray-900">{{ $application->student_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Course and year') }}</dt>
                            <dd class="mt-1 text-gray-900">{{ $application->course }} · {{ $application->year_level }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Scholarship type') }}</dt>
                            <dd class="mt-1 text-gray-900">{{ $application->scholarship_type }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Reason') }}</dt>
                            <dd class="mt-1 whitespace-pre-line text-gray-900">{{ $application->reason_for_applying ?: __('No reason provided.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
