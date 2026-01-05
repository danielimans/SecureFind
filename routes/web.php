<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Report Incident
    Route::get('/incident/report', [IncidentController::class, 'create'])
        ->name('incident.create');

    // Lost & Found
    Route::get('/lost-found', [LostFoundController::class, 'index'])
        ->name('lostfound.index');

    // My Reports
    Route::get('/my-reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    // Profile (update info & password)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
});

/* User dashboard (Student + Staff) */
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/* Admin dashboard */
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';
