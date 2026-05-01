<?php

use App\Models\Application;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    $user = auth()->user();
    $hasApplicationsTable = Schema::hasTable('applications');

    return view('welcome', [
        'dashboardRoute' => $user?->is_admin
            ? route('admin.applications.index')
            : route('dashboard'),
        'tallyFormUrl' => config('services.tally.form_url'),
        'stats' => [
            'applications' => $hasApplicationsTable ? Application::count() : 0,
            'pending' => $hasApplicationsTable ? Application::where('status', Application::STATUS_PENDING)->count() : 0,
            'approved' => $hasApplicationsTable ? Application::where('status', Application::STATUS_APPROVED)->count() : 0,
        ],
    ]);
})->middleware('no.cache');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'no.cache'])->name('dashboard');

Route::middleware(['auth', 'no.cache'])->group(function () {
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}', [NotificationController::class, 'update'])->name('notifications.update');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/applications', [AdminController::class, 'index'])->name('admin.applications.index');
        Route::patch('/admin/applications/{application}', [AdminController::class, 'update'])->name('admin.applications.update');
        Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
        Route::patch('/admin/users/{user}/promote', [UserManagementController::class, 'promote'])->name('admin.users.promote');
        Route::patch('/admin/users/{user}/demote', [UserManagementController::class, 'demote'])->name('admin.users.demote');
        Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
