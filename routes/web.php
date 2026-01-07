<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Single route pointing to controller
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Report Incident
    Route::get('/incident/report', [IncidentController::class, 'create'])
        ->name('incidents.report');

    Route::post('/incident/report', [IncidentController::class, 'store'])
        ->name('incidents.store');

    // Lost & Found
    Route::get('/lost-found', [LostFoundController::class, 'create'])
        ->name('lostfound.report');

    Route::post('/lost-found', [LostFoundController::class, 'store'])
        ->name('lostfound.store');

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

// Change Password//
Route::get('/password/change', [PasswordController::class, 'edit'])
    ->name('password.edit');

Route::post('/password/change', [PasswordController::class, 'update'])
    ->name('password.update');

/*Logout*/
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

/* Admin dashboard */
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';