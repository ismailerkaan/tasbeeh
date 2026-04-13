<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\ContentVersionController;
use App\Http\Controllers\Admin\ContentVersionIndexController;
use App\Http\Controllers\Admin\DailyZikrController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DuaCategoryController;
use App\Http\Controllers\Admin\DuaController;
use App\Http\Controllers\Admin\MobileFeedbackController;
use App\Http\Controllers\Admin\MobileUserController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\ZikirCategoryController;
use App\Http\Controllers\Admin\ZikirController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::view('/gizlilik-sozlesmesi', 'privacy-policy')->name('privacy.policy');

Route::get('/tasbeeh-dowloand', function (Request $request) {
    $userAgent = strtolower((string) $request->userAgent());

    $appStoreUrl = (string) env('TASBEEH_APP_STORE_URL', 'https://apps.apple.com/tr/search?term=tasbeeh');
    $playStoreUrl = (string) env('TASBEEH_PLAY_STORE_URL', 'https://play.google.com/store/search?q=tasbeeh&c=apps');

    if (str_contains($userAgent, 'android')) {
        return redirect()->away($playStoreUrl);
    }

    if (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad') || str_contains($userAgent, 'ipod')) {
        return redirect()->away($appStoreUrl);
    }

    return redirect()->away($playStoreUrl);
})->name('tasbeeh.download');

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
    Route::resource('/admin/mobile-feedbacks', MobileFeedbackController::class)
        ->only(['index', 'show', 'update'])
        ->names('admin.mobile-feedbacks');
    Route::resource('/admin/daily-zikrs', DailyZikrController::class)
        ->except('show')
        ->names('admin.daily-zikrs');
});
