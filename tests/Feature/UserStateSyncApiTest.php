<?php

use App\Models\ContentVersion;
use App\Models\Dua;
use App\Models\DuaCategory;
use App\Models\MobileUser;
use App\Models\Zikir;
use App\Models\ZikirCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user state sync endpoint stores user snapshot data', function () {
    $payload = [
        'userId' => 'u_k4m1l8p7x9fgy2',
        'fcmToken' => 'f7Yp_3x...L9zQ',
        'device' => [
            'name' => "Ahmet'in iPhone'u",
            'model' => 'iPhone 15 Pro',
            'os' => 'iOS',
            'version' => '17.4.1',
        ],
        'location' => [
            'city' => 'Istanbul',
            'district' => 'Uskudar',
        ],
        'lastZikir' => [
            'id' => '123',
            'name' => 'Subhanallah',
            'count' => 150,
        ],
        'readZikirs' => ['101', '105', '123'],
        'zikirCounts' => [
            '101' => 5,
            '105' => 12,
            '123' => 150,
        ],
        'readDuas' => ['d1', 'd7'],
        'isOptIn' => true,
        'totalZikirCount' => 1419,
        'streak' => [
            'current' => 6,
            'best' => 14,
            'totalActiveDays' => 28,
            'lastActiveDate' => '2026-04-06',
        ],
        'dailyActivitySummary' => [
            [
                'date' => '2026-04-05',
                'totalCount' => 18,
                'completedDailyZikr' => false,
            ],
            [
                'date' => '2026-04-06',
                'totalCount' => 27,
                'completedDailyZikr' => true,
            ],
        ],
        'zikirVersion' => 1,
        'duaVersion' => 1,
        'prayerTimesVersion' => 1,
        'updatedAt' => '2026-04-06T00:00:00Z',
    ];

    $response = $this->postJson(route('api.v1.user-state.sync'), $payload);

    $response
        ->assertOk()
        ->assertJsonPath('message', 'User state synchronized.')
        ->assertJsonPath('userId', 'u_k4m1l8p7x9fgy2')
        ->assertJsonPath('readZikirsCount', 3)
        ->assertJsonPath('zikirCountsTracked', 3)
        ->assertJsonPath('readDuasCount', 2)
        ->assertJsonPath('streak.current', 6)
        ->assertJsonPath('streak.best', 14)
        ->assertJsonPath('streak.totalActiveDays', 28)
        ->assertJsonPath('streak.lastActiveDate', '2026-04-06')
        ->assertJsonPath('has_updates', false)
        ->assertJsonPath('changed_modules', [])
        ->assertJsonPath('content', []);

    $user = MobileUser::query()->where('external_user_id', 'u_k4m1l8p7x9fgy2')->first();

    expect($user)->not->toBeNull();
    expect($user?->total_zikir_count)->toBe(1419);
    expect($user?->zikir_version)->toBe(1);
    expect($user?->dua_version)->toBe(1);
    expect($user?->prayer_times_version)->toBe(1);
    expect($user?->current_streak)->toBe(6);
    expect($user?->best_streak)->toBe(14);
    expect($user?->total_active_days)->toBe(28);
    expect($user?->last_active_date?->toDateString())->toBe('2026-04-06');
    expect(is_array($user?->daily_activity_summary))->toBeTrue();

    $this->assertDatabaseHas('mobile_user_devices', [
        'mobile_user_id' => $user?->id,
        'fcm_token' => 'f7Yp_3x...L9zQ',
        'device_model' => 'iPhone 15 Pro',
        'is_active' => 1,
    ]);

    $this->assertDatabaseHas('mobile_user_last_zikirs', [
        'mobile_user_id' => $user?->id,
        'content_id' => '123',
        'name' => 'Subhanallah',
        'count' => 150,
    ]);

    $this->assertDatabaseHas('mobile_user_read_zikirs', [
        'mobile_user_id' => $user?->id,
        'content_id' => '101',
    ]);

    $this->assertDatabaseHas('mobile_user_read_duas', [
        'mobile_user_id' => $user?->id,
        'content_id' => 'd1',
    ]);

    $this->assertDatabaseHas('mobile_user_zikir_counts', [
        'mobile_user_id' => $user?->id,
        'content_id' => '105',
        'count' => 12,
    ]);

    $this->assertDatabaseHas('device_push_tokens', [
        'token' => 'f7Yp_3x...L9zQ',
        'user_identifier' => 'u_k4m1l8p7x9fgy2',
        'is_active' => 1,
    ]);
});

test('user state sync endpoint reports changed modules using client versions', function () {
    $zikirCategory = ZikirCategory::factory()->create([
        'name' => 'Gunun Zikirleri',
        'description' => 'Sabah ve aksami zikirler',
        'is_active' => true,
    ]);
    $zikir = Zikir::factory()->create([
        'zikir_category_id' => $zikirCategory->id,
        'zikir' => 'Subhanallah',
        'anlami' => 'Allah noksanliklardan munezzehtir',
        'fazileti' => 'Kalbi ferahlatir',
        'hedef' => 100,
    ]);
    $duaCategory = DuaCategory::factory()->create([
        'name' => 'Sabah Dualari',
        'is_active' => true,
    ]);
    $dua = Dua::factory()->create([
        'dua_category_id' => $duaCategory->id,
        'dua' => 'Allahumme inni eseluke',
        'turkce_meali' => 'Allahim senden isterim',
        'source' => 'Hadis',
        'is_active' => true,
    ]);

    ContentVersion::query()->updateOrCreate(
        ['id' => 1],
        [
            'zikir_version' => 3,
            'dua_version' => 5,
            'prayer_times_version' => 4,
        ]
    );

    $response = $this->postJson(route('api.v1.user-state.sync'), [
        'userId' => 'u_sync_versions',
        'fcmToken' => 'token_sync_versions',
        'device' => [
            'name' => 'iPhone',
            'model' => 'iPhone 15',
            'os' => 'iOS',
            'version' => '17.5',
        ],
        'location' => [
            'city' => 'Bursa',
            'district' => 'Mudanya',
        ],
        'readZikirs' => [],
        'zikirCounts' => [
            '500' => 21,
        ],
        'readDuas' => [],
        'isOptIn' => true,
        'totalZikirCount' => 0,
        'zikirVersion' => 1,
        'duaVersion' => 2,
        'prayerTimesVersion' => 1,
        'updatedAt' => '2026-04-07T10:00:00Z',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('has_updates', true)
        ->assertJsonPath('changed_modules.0', 'zikir')
        ->assertJsonPath('changed_modules.1', 'dua')
        ->assertJsonPath('changed_modules.2', 'prayer_times')
        ->assertJsonPath('zikirCountsTracked', 1)
        ->assertJsonPath('versions.zikir_version', 3)
        ->assertJsonPath('versions.dua_version', 5)
        ->assertJsonPath('versions.prayer_times_version', 4)
        ->assertJsonPath('content.zikir.version', 3)
        ->assertJsonPath('content.zikir.data.0.kategori_adi', 'Gunun Zikirleri')
        ->assertJsonPath('content.zikir.data.0.zikirler.0.id', $zikir->id)
        ->assertJsonPath('content.zikir.data.0.zikirler.0.zikir', 'Subhanallah')
        ->assertJsonPath('content.dua.version', 5)
        ->assertJsonPath('content.dua.data.0.kategori', 'Sabah Dualari')
        ->assertJsonPath('content.dua.data.0.dualar.0.id', $dua->id)
        ->assertJsonPath('content.dua.data.0.dualar.0.dua', 'Allahumme inni eseluke');
});

test('user state sync endpoint validates required fields', function () {
    $response = $this->postJson(route('api.v1.user-state.sync'), [
        'userId' => 'u_1',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'fcmToken',
            'device',
            'isOptIn',
            'totalZikirCount',
            'updatedAt',
        ]);
});
