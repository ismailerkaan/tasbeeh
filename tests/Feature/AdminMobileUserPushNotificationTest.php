<?php

use App\Models\DevicePushToken;
use App\Models\MobileUser;
use App\Models\MobileUserTestAnswer;
use App\Models\MobileUserTestRun;
use App\Models\MobileUserTestStat;
use App\Models\TestLevel;
use App\Models\TestQuestion;
use App\Models\PushNotification;
use App\Models\User;
use App\Services\Push\FirebasePushService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('admin can see user notification actions on list and detail pages', function () {
    $mobileUser = MobileUser::query()->create([
        'external_user_id' => 'user_77',
        'city' => 'Istanbul',
        'district' => 'Kadikoy',
        'is_opt_in' => true,
        'total_zikir_count' => 12,
        'synced_at' => now(),
    ]);

    $this->get(route('admin.mobile-users.index'))
        ->assertOk()
        ->assertSee('Bildirim')
        ->assertSee(route('admin.mobile-users.push-notifications.store', $mobileUser), false);

    $this->get(route('admin.mobile-users.show', $mobileUser))
        ->assertOk()
        ->assertSee('Bu Kullanıcıya Bildirim Gönder')
        ->assertSee(route('admin.mobile-users.push-notifications.store', $mobileUser), false);
});

test('admin can send notification directly to a mobile user', function () {
    $this->app->instance(FirebasePushService::class, new class extends FirebasePushService
    {
        public array $sentTokens = [];

        /**
         * @param  array<string, mixed>  $data
         * @return array{success: bool, error: string|null}
         */
        public function sendToToken(string $token, string $title, string $body, array $data = []): array
        {
            $this->sentTokens[] = $token;

            return [
                'success' => true,
                'error' => null,
            ];
        }
    });

    $mobileUser = MobileUser::query()->create([
        'external_user_id' => 'user_77',
        'city' => 'Istanbul',
        'district' => 'Kadikoy',
        'is_opt_in' => true,
        'total_zikir_count' => 12,
        'synced_at' => now(),
    ]);

    DevicePushToken::factory()->create([
        'token' => 'target-token',
        'user_identifier' => 'user_77',
        'is_active' => true,
    ]);

    DevicePushToken::factory()->create([
        'token' => 'other-token',
        'user_identifier' => 'user_88',
        'is_active' => true,
    ]);

    $response = $this->post(route('admin.mobile-users.push-notifications.store', $mobileUser), [
        'title' => 'Ozel Bildirim',
        'body' => 'Bu bildirim sadece bu kullaniciya.',
        'data' => '{"screen":"profile"}',
    ]);

    $response
        ->assertRedirect()
        ->assertSessionHas('status', 'Bildirim gönderildi. Başarılı: 1, Hatalı: 0');

    $this->assertDatabaseHas('push_notifications', [
        'title' => 'Ozel Bildirim',
        'body' => 'Bu bildirim sadece bu kullaniciya.',
        'target_type' => PushNotification::TARGET_USER,
        'target_user_identifier' => 'user_77',
        'status' => PushNotification::STATUS_SENT,
        'success_count' => 1,
        'failed_count' => 0,
    ]);
});

test('mobile user notification validates json data', function () {
    $mobileUser = MobileUser::query()->create([
        'external_user_id' => 'user_77',
        'is_opt_in' => true,
    ]);

    $response = $this->post(route('admin.mobile-users.push-notifications.store', $mobileUser), [
        'title' => 'Baslik',
        'body' => 'Mesaj',
        'data' => '{hatali-json',
    ]);

    $response->assertSessionHasErrors('data');
});

test('admin can see mobile user test history and answers', function () {
    $mobileUser = MobileUser::query()->create([
        'external_user_id' => 'user_test_history',
        'is_opt_in' => true,
        'total_zikir_count' => 0,
    ]);

    MobileUserTestStat::query()->create([
        'mobile_user_id' => $mobileUser->id,
        'total_score' => 75,
        'best_run_score' => 50,
        'completed_runs' => 2,
        'answered_questions' => 6,
        'level_best_scores' => [],
    ]);

    $level = TestLevel::query()->create([
        'name' => 'Temel Seviye',
        'is_active' => true,
    ]);

    $question = TestQuestion::query()->create([
        'test_level_id' => $level->id,
        'question' => 'Test sorusu nedir?',
        'options' => ['Doğru şık', 'Yanlış şık'],
        'correct_option_key' => 'A',
        'is_active' => true,
    ]);

    $run = MobileUserTestRun::query()->create([
        'mobile_user_id' => $mobileUser->id,
        'test_level_id' => $level->id,
        'score' => 10,
        'correct_count' => 1,
        'total_questions' => 1,
        'best_streak' => 1,
        'continued_with_ad' => false,
        'ended_reason' => 'completed',
        'completed' => true,
        'ended_at' => now(),
    ]);

    MobileUserTestAnswer::query()->create([
        'mobile_user_test_run_id' => $run->id,
        'test_question_id' => $question->id,
        'question_order' => 1,
        'selected_option_id' => 'A',
        'correct_option_id' => 'A',
        'is_correct' => true,
        'score_earned' => 10,
    ]);

    $this->get(route('admin.mobile-users.index'))
        ->assertOk()
        ->assertSee('Test Puanı')
        ->assertSee('75');

    $this->get(route('admin.mobile-users.show', $mobileUser))
        ->assertOk()
        ->assertSee('Test Özeti')
        ->assertSee('Verdiği Cevaplar')
        ->assertSee('Temel Seviye')
        ->assertSee('Test sorusu nedir?')
        ->assertSee('Doğru şık')
        ->assertSee('Doğru');
});