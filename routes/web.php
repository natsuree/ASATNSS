<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
