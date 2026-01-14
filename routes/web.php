<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\IncidentsListsController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\ItemsListsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /* =========================
       Dashboard
    ========================= */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /* =========================
       Incident Reporting
    ========================= */
    Route::get('/incident/report', [IncidentController::class, 'create'])
        ->name('incidents.report');

    Route::post('/incident/report', [IncidentController::class, 'store'])
        ->name('incidents.store');

    /* =========================
       Lost & Found Reporting
    ========================= */
    Route::get('/lost-found/report', [LostFoundController::class, 'create'])
        ->name('lostfound.report');

    Route::post('/lost-found/report', [LostFoundController::class, 'store'])
        ->name('lostfound.store');

    /* =========================
       My Reports (Merged View)
    ========================= */
    Route::get('/my-reports', [ReportController::class, 'index'])
        ->name('reports.index');

    /* =========================
       Incident List & Details
    ========================= */
    Route::get('/incidents', [IncidentsListsController::class, 'index'])
        ->name('incidents.list');

    Route::get('/incidents/{incident}', [IncidentsListsController::class, 'show'])
        ->name('incidents.show');

    /* =========================
       Lost & Found List & Details
    ========================= */
    Route::get('/lost-found', [ItemsListsController::class, 'index'])
        ->name('lostfound.list');

    Route::get('/lost-found/{lostfound}', [ItemsListsController::class, 'show'])
        ->name('lostfound.show');

    /* =========================
       Edit / Update / Delete (My Reports)
    ========================= */
    Route::resource('incidents', IncidentController::class)
        ->only(['edit', 'update', 'destroy'])
        ->middleware('auth');

    Route::resource('lostfound', LostFoundController::class)
        ->only(['edit', 'update', 'destroy'])
        ->middleware('auth');

    /* =========================
       Notifications
    ========================= */
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    /* =========================
       Profile
    ========================= */
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile/update', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
    ->name('profile.destroy')
    ->middleware('auth');

    /* =========================
       Settings
    ========================= */
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php'; 
