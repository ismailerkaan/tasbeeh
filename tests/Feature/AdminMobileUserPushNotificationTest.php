<?php

use App\Models\DevicePushToken;
use App\Models\MobileUser;
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
