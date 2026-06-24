<?php

use App\Models\KaabaLiveStream;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can update kaaba live stream link', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('admin.kaaba-live-stream.update'), [
            'title' => 'Kâbe Canlı',
            'youtube_url' => 'https://www.youtube.com/live/abcdefghijk',
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.kaaba-live-stream.edit'));

    $this->assertDatabaseHas('kaaba_live_streams', [
        'title' => 'Kâbe Canlı',
        'youtube_url' => 'https://www.youtube.com/live/abcdefghijk',
        'is_active' => true,
    ]);
});

test('admin live stream link must belong to youtube', function () {
    $this->actingAs(User::factory()->create())
        ->put(route('admin.kaaba-live-stream.update'), [
            'title' => 'Kâbe Canlı',
            'youtube_url' => 'https://example.com/video',
            'is_active' => true,
        ])
        ->assertSessionHasErrors('youtube_url');
});

test('mobile api returns active kaaba live stream', function () {
    KaabaLiveStream::query()->create([
        'title' => 'Kâbe Canlı Yayını',
        'youtube_url' => 'https://youtu.be/abcdefghijk',
        'is_active' => true,
    ]);

    $this->getJson('/api/v1/kaaba-live-stream')
        ->assertOk()
        ->assertJsonPath('data.video_id', 'abcdefghijk')
        ->assertJsonPath('data.embed_url', 'https://www.youtube.com/embed/abcdefghijk');
});

test('mobile api hides inactive kaaba live stream', function () {
    KaabaLiveStream::query()->create([
        'title' => 'Kâbe Canlı Yayını',
        'youtube_url' => 'https://youtu.be/abcdefghijk',
        'is_active' => false,
    ]);

    $this->getJson('/api/v1/kaaba-live-stream')
        ->assertOk()
        ->assertJsonPath('data', null);
});
