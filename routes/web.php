<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\IncidentsListsController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ItemsListsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Report Incident
    Route::get('/incident/report', [IncidentController::class, 'create'])
        ->name('incidents.report');

    Route::post('/incident/report', [IncidentController::class, 'store'])
        ->name('incidents.store');

    // Lost & Found
    Route::get('/lost-found/report', [LostFoundController::class, 'create'])
        ->name('lostfound.report');

    Route::post('/lost-found/report', [LostFoundController::class, 'store'])
        ->name('lostfound.store');

    // My Reports
    Route::get('/my-reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // All Incidents
    Route::get('/incidents', [IncidentsListsController::class, 'index'])
        ->name('incidents.list');

    Route::get('/incidents/{id}', [IncidentsListsController::class, 'show'])
        ->name('incidents.show');

    // All Lost & Found
    Route::get('/lost-found', [ItemsListsController::class, 'index'])
        ->name('lostfound.list');

    Route::get('/lost-found/{id}', [ItemsListsController::class, 'show'])
        ->name('lostfound.show');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    // Profile Routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    
    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');
    
    Route::delete('/profile/destroy', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');

});

// Logout //
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Admin dashboard //
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';