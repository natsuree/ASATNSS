<x-app-layout>
    <x-slot name="header">
        <h2 class="asat-page-title">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="asat-section">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(Auth::user()->is_admin)
                <!-- Admin Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="asat-card p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Applications</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Application::count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="asat-card p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending Review</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Application::where('status', 'Pending')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="asat-card p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Approved</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Application::where('status', 'Approved')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="asat-card p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Rejected</p>
                                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Application::where('status', 'Rejected')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- User Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="asat-card p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">My Applications</p>
                                <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->applications()->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="asat-card p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Approved</p>
                                <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->applications()->where('status', 'Approved')->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="asat-card p-6">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->applications()->where('status', 'Pending')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="asat-card">
                <div class="grid gap-0 lg:grid-cols-[1.25fr_.75fr]">
                    <div class="p-8">
                        <p class="text-sm font-bold uppercase tracking-wider text-[var(--asat-teal)]">Scholarship operations</p>
                        <h3 class="mt-3 text-2xl font-extrabold text-[var(--asat-ink)]">{{ __('ASATNSS Scholarship Portal') }}</h3>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">{{ __('Submit scholarship applications, monitor review status, and receive updates from the admin office in one secure dashboard.') }}</p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('applications.index') }}" class="asat-action-primary inline-flex items-center rounded-md px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('View applications') }}
                            </a>
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-700 transition hover:bg-slate-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Update profile') }}
                            </a>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 bg-slate-50 p-8 lg:border-l lg:border-t-0">
                        <h4 class="text-sm font-extrabold uppercase tracking-wider text-slate-600">System modules</h4>
                        <div class="mt-5 space-y-4">
                            <div class="asat-card-muted p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-bold text-slate-900">Student applications</p>
                                        <p class="mt-1 text-sm text-slate-600">Form submission and status tracking</p>
                                    </div>
                                </div>
                            </div>
                            <div class="asat-card-muted p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h4l4 4v-4h4a2 2 0 002-2z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-bold text-slate-900">Notifications</p>
                                        <p class="mt-1 text-sm text-slate-600">Status alerts and admin updates</p>
                                    </div>
                                </div>
                            </div>
                            <div class="asat-card-muted p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    <div>
                                        <p class="font-bold text-slate-900">Integrations</p>
                                        <p class="mt-1 text-sm text-slate-600">Tally webhook and Abstract reputation checks</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
