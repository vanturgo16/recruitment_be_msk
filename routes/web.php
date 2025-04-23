<?php

use Illuminate\Support\Facades\Route;

// MIDDLEWARE
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\NoCache;
use App\Http\Middleware\UpdateLastSeen;
// CONTROLLER
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlacklistController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MstDepartmentController;
use App\Http\Controllers\MstDivisionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MstDropdownController;
use App\Http\Controllers\MstPositionController;
use App\Http\Controllers\MstRuleController;
use App\Http\Controllers\MstUserController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\AjaxMappingRegional;
use App\Http\Controllers\JoblistController;

// LOGIN
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");
// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/expired-logout', [AuthController::class, 'expiredlogout'])->name('expiredlogout');

Route::get('/change-language/{lang}', [LanguageController::class, 'change'])->name('change.language');

// LOGGED IN
Route::middleware([Authenticate::class, NoCache::class, UpdateLastSeen::class])->group(function () {
    // PROFIL
    Route::controller(ProfileController::class)->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('/', 'index')->name('profile.index');
            Route::post('/update-photo', 'updatePhoto')->name('profile.updatePhoto');
        });
    });
    // DASHBOARD
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::post('/', 'switchTheme')->name('switchTheme');
        });
    });

    // CONFIGURATION
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

    // MASTER DATA
    // OFFICE
    Route::middleware(['role:Admin,Super Admin'])->controller(OfficeController::class)->group(function () {
        Route::prefix('office')->group(function () {
            Route::get('/', 'index')->name('office.index');
            Route::post('/store', 'store')->name('office.store');
            Route::get('/edit/{id}', 'edit')->name('office.edit');
            Route::post('/update/{id}', 'update')->name('office.update');
            Route::post('/activate/{id}', 'activate')->name('office.activate');
            Route::post('/deactivate/{id}', 'deactivate')->name('office.deactivate');
        });
    });
    // DIVISION
    Route::middleware(['role:Admin,Super Admin'])->controller(MstDivisionController::class)->group(function () {
        Route::prefix('division')->group(function () {
            Route::get('/', 'index')->name('division.index');
            Route::post('/store', 'store')->name('division.store');
            Route::post('/update/{id}', 'update')->name('division.update');
        });
    });
    // DEPARTMENT
    Route::middleware(['role:Admin,Super Admin'])->controller(MstDepartmentController::class)->group(function () {
        Route::prefix('department')->group(function () {
            Route::get('/', 'index')->name('department.index');
            Route::post('/store', 'store')->name('department.store');
            Route::post('/update/{id}', 'update')->name('department.update');
        });
    });
    // POSITION
    Route::middleware(['role:Admin,Super Admin'])->controller(MstPositionController::class)->group(function () {
        Route::prefix('position')->group(function () {
            Route::get('/', 'index')->name('position.index');
            Route::post('/store', 'store')->name('position.store');
            Route::post('/update/{id}', 'update')->name('position.update');
        });
    });
    // EMPLOYEE
    Route::middleware(['role:Admin,Super Admin'])->controller(EmployeeController::class)->group(function () {
        Route::prefix('employee')->group(function () {
            Route::get('/', 'index')->name('employee.index');
            Route::get('/detail/{id}', 'detail')->name('employee.detail');
        });
    });
    // BLACKLIST
    Route::middleware(['role:Admin,Super Admin'])->controller(BlacklistController::class)->group(function () {
        Route::prefix('blacklist')->group(function () {
            Route::get('/', 'index')->name('blacklist.index');
            Route::post('/store', 'store')->name('blacklist.store');
            Route::post('/update/{id}', 'update')->name('blacklist.update');
            Route::post('/delete/{id}', 'delete')->name('blacklist.delete');
        });
    });

    // RECRUITMENT
    // JOBLIST
    Route::middleware(['role:Admin,Super Admin'])->controller(JoblistController::class)->group(function () {
        Route::prefix('joblist')->group(function () {
            Route::get('/', 'index')->name('joblist.index');
            Route::post('/store', 'store')->name('joblist.store');
            Route::get('/detail/{id}', 'detail')->name('joblist.detail');
            Route::get('/applicant-list/{id}', 'applicantList')->name('joblist.applicantList');
            Route::post('/update/{id}', 'update')->name('joblist.update');
            Route::post('/delete/{id}', 'delete')->name('joblist.delete');
            Route::post('/activate/{id}', 'activate')->name('joblist.activate');
            Route::post('/deactivate/{id}', 'deactivate')->name('joblist.deactivate');
            Route::get('/get-users-by-position/{id}', 'getUsersByPosition')->name('joblist.getuser');
        });
    });

    // OTHER
    // AUDIT LOG
    Route::middleware(['role:Admin,Super Admin'])->controller(AuditLogController::class)->group(function () {
        Route::prefix('auditlog')->group(function () {
            Route::get('/', 'index')->name('auditlog.index');
        });
    });

    // API REGIONAL
    Route::controller(AjaxMappingRegional::class)->group(function () {
        Route::prefix('area/ajax')->group(function () {
            Route::get('/mappingCity/{province_id}', 'selectCity')->name('mappingCity');
            Route::get('/mappingDistrict/{city_id}', 'selectDistrict')->name('mappingDistrict');
            Route::get('/mappingSubDistrict/{district_id}', 'selectSubDistrict')->name('mappingSubDistrict');
        });
    });
});
