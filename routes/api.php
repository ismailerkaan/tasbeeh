<?php

use App\Http\Controllers\Api\ContentCheckController;
use App\Http\Controllers\Api\ContentDataController;
use App\Http\Controllers\Api\DailyZikrController;
use App\Http\Controllers\Api\KaabaLiveStreamController;
use App\Http\Controllers\Api\PushTokenController;
use App\Http\Controllers\Api\ReligiousSpecialDayController;
use App\Http\Controllers\Api\SpecialDaySharingImageController;
use App\Http\Controllers\Api\SpecialDaySharingPopupController;
use App\Http\Controllers\Api\PrayerTimesController;
use App\Http\Controllers\Api\StoreMobileFeedbackController;
use App\Http\Controllers\Api\SyncUserStateController;
use App\Http\Controllers\Api\TestController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('/content/check', ContentCheckController::class)->name('api.v1.content.check');
    Route::get('/content/zikirler', [ContentDataController::class, 'zikirs'])->name('api.v1.content.zikirs');
    Route::get('/content/dualar', [ContentDataController::class, 'duas'])->name('api.v1.content.duas');
    Route::get('/content/hadisler', [ContentDataController::class, 'hadises'])->name('api.v1.content.hadises');
    Route::get('/daily-zikr', DailyZikrController::class)->name('api.v1.daily-zikr.show');
    Route::get('/kaaba-live-stream', KaabaLiveStreamController::class)->name('api.v1.kaaba-live-stream.show');
    Route::get('/religious-special-days', ReligiousSpecialDayController::class)->name('api.v1.religious-special-days.index');
    Route::get('/special-day-sharing-popup', SpecialDaySharingPopupController::class)->name('api.v1.special-day-sharing-popup.show');
    Route::get('/special-day-sharing-images/{image}', SpecialDaySharingImageController::class)->name('api.v1.special-day-sharing-images.show');
    Route::get('/prayer-times', PrayerTimesController::class)->name('api.v1.prayer-times.show');
    Route::get('/tests/levels', [TestController::class, 'levels'])->name('api.v1.tests.levels');
    Route::get('/tests/levels/{level}/questions', [TestController::class, 'questionsByLevel'])->name('api.v1.tests.levels.questions');
    Route::get('/tests/questions', [TestController::class, 'questions'])->name('api.v1.tests.questions');
    Route::post('/push-tokens', PushTokenController::class)->name('api.v1.push-tokens.store');
    Route::post('/user-state/sync', SyncUserStateController::class)->name('api.v1.user-state.sync');
    Route::post('/feedback', StoreMobileFeedbackController::class)->name('api.v1.feedback.store');
});
