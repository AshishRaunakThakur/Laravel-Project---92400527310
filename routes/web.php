<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampusCatalogController;
use App\Http\Controllers\CleaningRecordController;
use App\Http\Controllers\CleaningRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StaffManagementController;
use App\Http\Controllers\StaffTaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Auth::check() ? redirect()->route('dashboard') : view('home'))->name('home');
Route::middleware('guest')->group(function () {
    Route::get('/login',[AuthController::class,'loginForm'])->name('login');
    Route::post('/login',[AuthController::class,'login'])->middleware('throttle:10,1')->name('login.store');
    Route::get('/register',[AuthController::class,'registerForm'])->name('register');
    Route::post('/register',[AuthController::class,'register'])->middleware('throttle:5,1')->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/dashboard',[DashboardController::class,'index'])->middleware('role:admin,staff,campus_user')->name('dashboard');
    Route::view('/profile','profile')->name('profile');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('records',CleaningRecordController::class)->names('records');
        Route::get('/requests',[CleaningRequestController::class,'adminIndex'])->name('requests.index');
        Route::post('/requests/{cleaningRequest}/approve',[CleaningRequestController::class,'approve'])->name('requests.approve');
        Route::post('/requests/{cleaningRequest}/reject',[CleaningRequestController::class,'reject'])->name('requests.reject');
        Route::get('/reports',[ReportController::class,'index'])->name('reports.index');
        Route::get('/reports/export',[ReportController::class,'export'])->name('reports.export');
        Route::get('/staff',[StaffManagementController::class,'index'])->name('staff.index');
        Route::get('/staff/create',[StaffManagementController::class,'create'])->name('staff.create');
        Route::post('/staff',[StaffManagementController::class,'store'])->name('staff.store');
        Route::get('/staff/{member}',[StaffManagementController::class,'show'])->name('staff.show');
        Route::get('/staff/{member}/edit',[StaffManagementController::class,'edit'])->name('staff.edit');
        Route::put('/staff/{member}',[StaffManagementController::class,'update'])->name('staff.update');
        Route::patch('/staff/{member}/active',[StaffManagementController::class,'toggle'])->name('staff.toggle');
        Route::get('/locations',[CampusCatalogController::class,'locations'])->name('locations.index');
        Route::post('/locations',[CampusCatalogController::class,'storeLocation'])->name('locations.store');
        Route::put('/locations/{location}',[CampusCatalogController::class,'updateLocation'])->name('locations.update');
        Route::delete('/locations/{location}',[CampusCatalogController::class,'deleteLocation'])->name('locations.destroy');
        Route::patch('/locations/{location}/active',[CampusCatalogController::class,'toggleLocation'])->name('locations.toggle');
        Route::get('/categories',[CampusCatalogController::class,'categories'])->name('categories.index');
        Route::post('/categories',[CampusCatalogController::class,'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}',[CampusCatalogController::class,'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}',[CampusCatalogController::class,'deleteCategory'])->name('categories.destroy');
        Route::patch('/categories/{category}/active',[CampusCatalogController::class,'toggleCategory'])->name('categories.toggle');
        Route::get('/priorities',[CampusCatalogController::class,'priorities'])->name('priorities.index');
        Route::patch('/priorities/{priority}/active',[CampusCatalogController::class,'togglePriority'])->name('priorities.toggle');
    });

    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/tasks/{record}',[StaffTaskController::class,'show'])->name('tasks.show');
        Route::patch('/tasks/{record}',[StaffTaskController::class,'update'])->name('tasks.update');
    });

    Route::middleware('role:campus_user')->group(function () {
        Route::get('/requests/create',[CleaningRequestController::class,'create'])->name('requests.create');
        Route::post('/requests',[CleaningRequestController::class,'store'])->name('requests.store');
    });
    Route::get('/requests/{cleaningRequest}',[CleaningRequestController::class,'show'])->name('requests.show');
});

Route::view('/forbidden','errors.403')->name('forbidden');
