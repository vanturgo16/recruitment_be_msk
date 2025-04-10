<?php

use Illuminate\Support\Facades\Route;

// MIDDLEWARE
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\NoCache;
use App\Http\Middleware\UpdateLastSeen;
// CONTROLLER
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MstDropdownController;
use App\Http\Controllers\MstRuleController;
use App\Http\Controllers\MstUserController;

// LOGIN
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");
// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/expired-logout', [AuthController::class, 'expiredlogout'])->name('expiredlogout');

Route::get('/change-language/{lang}', [LanguageController::class, 'change'])->name('change.language');

// LOGGED IN
Route::middleware([Authenticate::class, NoCache::class, UpdateLastSeen::class])->group(function () {
    // DASHBOARD
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::post('/', 'switchTheme')->name('switchTheme');
        });
    });
    // PROFIL
    Route::controller(ProfileController::class)->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('/', 'index')->name('profile.index');
            Route::post('/update-photo', 'updatePhoto')->name('profile.updatePhoto');
        });
    });
    // RULE CONFIGURATION
    Route::middleware(['role:Super Admin'])->controller(MstRuleController::class)->group(function () {
        Route::prefix('rule')->group(function () {
            Route::get('/', 'index')->name('rule.index');
            Route::post('/store', 'store')->name('rule.store');
            Route::post('/update/{id}', 'update')->name('rule.update');
            Route::post('/delete/{id}', 'delete')->name('rule.delete');
        });
    });
    // DROPDOWN CONFIGURATION
    Route::middleware(['role:Admin,Super Admin'])->controller(MstDropdownController::class)->group(function () {
        Route::prefix('dropdown')->group(function () {
            Route::get('/', 'index')->name('dropdown.index');
            Route::post('/store', 'store')->name('dropdown.store');
            Route::post('/update/{id}', 'update')->name('dropdown.update');
            Route::post('/disable/{id}', 'disable')->name('dropdown.disable');
            Route::post('/enable/{id}', 'enable')->name('dropdown.enable');
        });
    });
    // USER CONFIGURATION
    Route::middleware(['role:Admin,Super Admin'])->controller(MstUserController::class)->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', 'index')->name('user.index');
            Route::get('/datas', 'datas')->name('user.datas');
            Route::post('/store', 'store')->name('user.store');
            Route::get('/edit/{id}', 'edit')->name('user.edit');
            Route::post('/update/{id}', 'update')->name('user.update');
            Route::post('/reset/{id}', 'reset')->name('user.reset');
            Route::post('/activate/{id}', 'activate')->name('user.activate');
            Route::post('/deactivate/{id}', 'deactivate')->name('user.deactivate');
            Route::post('/delete/{id}', 'delete')->name('user.delete');
            Route::post('/check_email_employee', 'check_email')->name('user.check_email_employee');
        });
    });
    // AUDIT LOG
    Route::middleware(['role:Admin,Super Admin'])->controller(AuditLogController::class)->group(function () {
        Route::prefix('auditlog')->group(function () {
            Route::get('/', 'index')->name('auditlog.index');
        });
    });
});
