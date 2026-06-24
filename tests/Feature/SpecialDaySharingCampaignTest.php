<?php

use App\Models\SpecialDaySharingCampaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('admin can create a scheduled sharing campaign with multiple images', function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create())
        ->post(route('admin.special-day-sharing-campaigns.store'), [
            'title' => 'Kadir Geceniz Mübarek Olsun',
            'message' => 'Dualarınız kabul olsun.',
            'publish_date' => '2026-06-24',
            'is_active' => '1',
            'images' => [
                UploadedFile::fake()->image('birinci.jpg', 1080, 1350),
                UploadedFile::fake()->image('ikinci.png', 1080, 1350),
            ],
        ])
        ->assertRedirect(route('admin.special-day-sharing-campaigns.index'));

    $campaign = SpecialDaySharingCampaign::query()->with('images')->firstOrFail();
    expect($campaign->title)->toBe('Kadir Geceniz Mübarek Olsun')
        ->and($campaign->message)->toBe('Dualarınız kabul olsun.')
        ->and($campaign->images)->toHaveCount(2);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.special-day-sharing-campaigns.index'))
        ->assertOk()
        ->assertSee('Kadir Geceniz Mübarek Olsun');
});

test('mobile popup api returns active campaign only on its publish date', function () {
    Storage::fake('public');
    $this->travelTo(now()->setDate(2026, 6, 24));

    $campaign = SpecialDaySharingCampaign::query()->create([
        'title' => 'Bayramınız Mübarek Olsun',
        'message' => 'Sevdiklerinizle huzurlu bir bayram dileriz.',
        'publish_date' => '2026-06-24',
        'is_active' => true,
    ]);
    $campaign->images()->create([
        'path' => UploadedFile::fake()->image('bayram.jpg')->store('special-day-sharing', 'public'),
        'original_name' => 'bayram.jpg',
        'sort_order' => 1,
    ]);

    $response = $this->getJson(route('api.v1.special-day-sharing-popup.show'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Bayramınız Mübarek Olsun')
        ->assertJsonPath('data.0.message', 'Sevdiklerinizle huzurlu bir bayram dileriz.')
        ->assertJsonCount(1, 'data.0.images');

    $this->get($response->json('data.0.images.0.url'))->assertOk();

    $this->travelTo(now()->addDay());
    $this->getJson(route('api.v1.special-day-sharing-popup.show'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
