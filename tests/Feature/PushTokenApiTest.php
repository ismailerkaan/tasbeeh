<?php

use App\Models\DevicePushToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('mobile client can store push token', function () {
    $response = $this->postJson(route('api.v1.push-tokens.store'), [
        'token' => 'token_123',
        'user_identifier' => 'user_1',
        'platform' => 'android',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('message', 'Push token kaydedildi.');

    $this->assertDatabaseHas('device_push_tokens', [
        'token' => 'token_123',
        'user_identifier' => 'user_1',
        'platform' => 'android',
        'is_active' => 1,
    ]);
});

test('existing token can be updated with new user identifier', function () {
    DevicePushToken::factory()->create([
        'token' => 'token_abc',
        'user_identifier' => 'old_user',
        'platform' => 'ios',
        'is_active' => false,
    ]);

    $this->postJson(route('api.v1.push-tokens.store'), [
        'token' => 'token_abc',
        'user_identifier' => 'new_user',
        'platform' => 'ios',
    ])->assertOk();

    $this->assertDatabaseHas('device_push_tokens', [
        'token' => 'token_abc',
        'user_identifier' => 'new_user',
        'is_active' => 1,
    ]);
});

test('push token endpoint validates payload', function () {
    $response = $this->postJson(route('api.v1.push-tokens.store'), [
        'platform' => 'web',
    ]);

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['token', 'platform']);
});
