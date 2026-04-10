<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContentVersionController;
use App\Http\Controllers\Admin\ContentVersionIndexController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ZikirCategoryController;
use App\Http\Controllers\Admin\ZikirController;
use App\Http\Controllers\Admin\DuaCategoryController;
use App\Http\Controllers\Admin\DuaController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\MobileUserController;
use App\Http\Controllers\Admin\DailyZikrController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::redirect('/login', '/admin/login')->name('login');
    Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'store'])->name('admin.login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');

    Route::get('/admin', DashboardController::class)->name('admin.dashboard');
    Route::get('/admin/content-versions', ContentVersionIndexController::class)->name('admin.content-versions.index');
    Route::post('/admin/content-versions/bump', ContentVersionController::class)->name('admin.content-versions.bump');
    Route::resource('/admin/zikir-categories', ZikirCategoryController::class)
        ->except('show')
        ->names('admin.zikir-categories');
    Route::resource('/admin/zikirs', ZikirController::class)
        ->except('show')
        ->names('admin.zikirs');
    Route::resource('/admin/dua-categories', DuaCategoryController::class)
        ->except('show')
        ->names('admin.dua-categories');
    Route::resource('/admin/duas', DuaController::class)
        ->except('show')
        ->names('admin.duas');
    Route::resource('/admin/push-notifications', PushNotificationController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names('admin.push-notifications');
    Route::resource('/admin/mobile-users', MobileUserController::class)
        ->names('admin.mobile-users');
    Route::resource('/admin/daily-zikrs', DailyZikrController::class)
        ->except('show')
        ->names('admin.daily-zikrs');
});
