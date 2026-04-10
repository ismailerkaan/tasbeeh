<?php

use App\Jobs\SendPushNotificationJob;
use App\Models\PushNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

test('admin push notifications index page is accessible', function () {
    $response = $this->get(route('admin.push-notifications.index'));

    $response
        ->assertOk()
        ->assertSee('Push Bildirimleri')
        ->assertSee('Yeni Bildirim');
});

test('admin can queue notification to all users', function () {
    Queue::fake();

    $response = $this->post(route('admin.push-notifications.store'), [
        'title' => 'Yeni İçerik',
        'body' => 'Yeni dua eklendi.',
        'target_type' => 'all',
        'data' => '{"screen":"duas"}',
    ]);

    $response
        ->assertRedirect(route('admin.push-notifications.index'))
        ->assertSessionHas('status');

    $notification = PushNotification::query()->first();

    expect($notification)->not->toBeNull();
    expect($notification?->target_type)->toBe('all');
    expect($notification?->status)->toBe('queued');

    Queue::assertPushed(SendPushNotificationJob::class);
});

test('admin can queue notification to specific user', function () {
    Queue::fake();

    $response = $this->post(route('admin.push-notifications.store'), [
        'title' => 'Özel Mesaj',
        'body' => 'Bu bildirim sadece sana.',
        'target_type' => 'user',
        'target_user_identifier' => 'user_77',
    ]);

    $response->assertRedirect(route('admin.push-notifications.index'));

    $this->assertDatabaseHas('push_notifications', [
        'target_type' => 'user',
        'target_user_identifier' => 'user_77',
    ]);

    Queue::assertPushed(SendPushNotificationJob::class);
});

test('notification create validates target user when needed', function () {
    $response = $this->post(route('admin.push-notifications.store'), [
        'title' => 'Başlık',
        'body' => 'İçerik',
        'target_type' => 'user',
    ]);

    $response->assertSessionHasErrors('target_user_identifier');
});

test('admin can edit queued notification', function () {
    $notification = PushNotification::factory()->create([
        'status' => PushNotification::STATUS_QUEUED,
        'target_type' => PushNotification::TARGET_ALL,
    ]);

    $response = $this->get(route('admin.push-notifications.edit', $notification));

    $response
        ->assertOk()
        ->assertSee('Kuyruktaki Bildirimi Düzenle');
});

test('edit page redirects when notification is not queued', function () {
    $notification = PushNotification::factory()->create([
        'status' => PushNotification::STATUS_SENT,
    ]);

    $response = $this->get(route('admin.push-notifications.edit', $notification));

    $response
        ->assertRedirect(route('admin.push-notifications.index'))
        ->assertSessionHas('status');
});

test('admin can update queued notification', function () {
    $notification = PushNotification::factory()->create([
        'status' => PushNotification::STATUS_QUEUED,
        'target_type' => PushNotification::TARGET_ALL,
    ]);

    $response = $this->put(route('admin.push-notifications.update', $notification), [
        'title' => 'Güncel Başlık',
        'body' => 'Güncel içerik',
        'target_type' => 'user',
        'target_user_identifier' => 'user_88',
        'data' => '{"foo":"bar"}',
    ]);

    $response
        ->assertRedirect(route('admin.push-notifications.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('push_notifications', [
        'id' => $notification->id,
        'title' => 'Güncel Başlık',
        'target_type' => 'user',
        'target_user_identifier' => 'user_88',
    ]);
});

test('admin can cancel queued notification', function () {
    $notification = PushNotification::factory()->create([
        'status' => PushNotification::STATUS_QUEUED,
    ]);

    $response = $this->delete(route('admin.push-notifications.destroy', $notification));

    $response
        ->assertRedirect(route('admin.push-notifications.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('push_notifications', [
        'id' => $notification->id,
        'status' => PushNotification::STATUS_CANCELED,
    ]);
});
