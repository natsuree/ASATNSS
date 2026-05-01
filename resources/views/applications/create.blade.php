<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="asat-page-kicker">{{ __('Student Intake') }}</p>
            <h2 class="asat-page-title">
                {{ __('New Scholarship Application') }}
            </h2>
        </div>
    </x-slot>

    <div class="asat-section">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="asat-card">
                <form method="POST" action="{{ route('applications.store') }}">
                    @csrf

                    <div class="asat-card-header">
                        <p class="asat-page-kicker">Application record</p>
                        <h3 class="mt-2 text-xl font-black text-[var(--asat-ink)]">Scholarship application form</h3>
                    </div>

                    <div class="grid gap-0 lg:grid-cols-[240px_1fr]">
                        <aside class="border-b border-slate-200 bg-slate-50 p-6 lg:border-b-0 lg:border-r">
                            <p class="text-sm font-black text-slate-900">{{ __('Applicant Details') }}</p>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('These fields become the official application record for review.') }}</p>
                        </aside>

                        <div class="space-y-6 p-6">
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <x-input-label for="full_name" :value="__('Full name')" />
                                    <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', auth()->user()->name)" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', auth()->user()->email)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <x-input-label for="student_id" :value="__('Student ID')" />
                                    <x-text-input id="student_id" name="student_id" type="text" class="mt-1 block w-full" :value="old('student_id')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('student_id')" />
                                </div>

                                <div>
                                    <x-input-label for="course" :value="__('Course')" />
                                    <x-text-input id="course" name="course" type="text" class="mt-1 block w-full" :value="old('course')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('course')" />
                                </div>

                                <div>
                                    <x-input-label for="year_level" :value="__('Year level')" />
                                    <select id="year_level" name="year_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                                        @foreach (['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'] as $year)
                                            <option value="{{ $year }}" @selected(old('year_level') === $year)>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('year_level')" />
                                </div>

                                <div>
                                    <x-input-label for="scholarship_type" :value="__('Scholarship type')" />
                                    <select id="scholarship_type" name="scholarship_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" required>
                                        @foreach (['Academic', 'Financial Assistance', 'Athletic', 'Leadership'] as $type)
                                            <option value="{{ $type }}" @selected(old('scholarship_type') === $type)>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('scholarship_type')" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="reason_for_applying" :value="__('Reason for applying')" />
                                <textarea id="reason_for_applying" name="reason_for_applying" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">{{ old('reason_for_applying') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('reason_for_applying')" />
                            </div>

                            <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-end">
                                <a href="{{ route('applications.index') }}" class="asat-button asat-button-secondary">{{ __('Cancel') }}</a>
                                <x-primary-button>{{ __('Submit application') }}</x-primary-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
