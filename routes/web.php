<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SceneCaseController;
use App\Http\Controllers\SceneCasePhotoController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterData\BodyHandlingController;
use App\Http\Controllers\MasterData\GenderController;
use App\Http\Controllers\MasterData\NotificationTypeController;
use App\Http\Controllers\MasterData\PoliceStationController;
use App\Http\Controllers\MasterData\LabController;
use App\Http\Controllers\MasterData\AutopsyAssistantController;
use App\Http\Controllers\MasterData\PhotoAssistantController;
use App\Http\Controllers\AutopsyCaseController;
use App\Http\Controllers\ApproveAutopsyCaseController;
use App\Http\Controllers\SceneServiceFeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');


    Route::resource('scene-cases', SceneCaseController::class);
    Route::get('scene-case-shifts/{shift}', [SceneCaseController::class, 'shiftInfo'])->name('scene-cases.shift-info');
    Route::delete('scene-case-photos/{photo}', [SceneCasePhotoController::class, 'destroy'])->name('scene-case-photos.destroy');
    
    Route::middleware('permission:manage scene service fee')->group(function () {
        Route::get('scene-service-fee', [SceneServiceFeeController::class, 'index'])->name('scene-service-fee.index');
    });

    Route::middleware('permission:manage autopsy cases')->group(function () {
        Route::middleware('role_or_permission:staff')->group(function () {
             Route::get('scene-cases/{scene}/autopsy-cases/create', [AutopsyCaseController::class, 'create'])
        ->name('scene-cases.autopsy-cases.create');
        Route::resource('autopsy-cases', AutopsyCaseController::class)->except(['create','destroy','show']);
        });

        Route::get('autopsy-cases/{autopsyCase}', [AutopsyCaseController::class,'show'])->name('autopsy-cases.show');
        Route::get('approve-autopsy-cases', [ApproveAutopsyCaseController::class,'index'])->name('approve-autopsy-cases.index');
        Route::get('approve-autopsy-cases/{autopsy_id}', [ApproveAutopsyCaseController::class,'submitted'])->name('approve-autopsy-cases.submitted');
    });

    Route::middleware('permission:manage shifts')->group(function () {
        Route::get('/shifts-events', [ShiftController::class, 'events'])->name('shifts.events');
        Route::resource('shifts', ShiftController::class)->except(['show']);
    });

    Route::middleware('role_or_permission:admin|view all reports|view own reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    Route::middleware('permission:manage users')->group(function () {
        Route::post('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
        Route::resource('users', UserController::class)->except(['destroy']);
    });

    Route::middleware('permission:manage roles')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show', 'destroy']);
    });

    Route::middleware('permission:manage permissions')->group(function () {
        Route::resource('permissions', PermissionController::class)->except(['show', 'destroy']);
    });

    Route::prefix('master-data')->name('master-data.')->middleware('permission:manage master data')->group(function () {
        Route::resource('police-stations', PoliceStationController::class)->except(['show']);
        Route::resource('body-handlings', BodyHandlingController::class)->except(['show']);
        Route::resource('notification-types', NotificationTypeController::class)->except(['show']);
        Route::resource('genders', GenderController::class)->except(['show']);
        Route::resource('labs', LabController::class)->except(['show']);
        Route::resource('autopsy-assistants', AutopsyAssistantController::class)->except(['show']);
        Route::resource('photo-assistants', PhotoAssistantController::class)->except(['show']);
    });
});

require __DIR__.'/settings.php';