<?php

use App\Http\Controllers\Api\ContentCheckController;
use App\Http\Controllers\Api\ContentDataController;
use App\Http\Controllers\Api\DailyZikrController;
use App\Http\Controllers\Api\PushTokenController;
use App\Http\Controllers\Api\StoreMobileFeedbackController;
use App\Http\Controllers\Api\SyncUserStateController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/content/check', ContentCheckController::class)->name('api.v1.content.check');
    Route::get('/content/zikirler', [ContentDataController::class, 'zikirs'])->name('api.v1.content.zikirs');
    Route::get('/content/dualar', [ContentDataController::class, 'duas'])->name('api.v1.content.duas');
    Route::get('/daily-zikr', DailyZikrController::class)->name('api.v1.daily-zikr.show');
    Route::post('/push-tokens', PushTokenController::class)->name('api.v1.push-tokens.store');
    Route::post('/user-state/sync', SyncUserStateController::class)->name('api.v1.user-state.sync');
    Route::post('/feedback', StoreMobileFeedbackController::class)->name('api.v1.feedback.store');
});
