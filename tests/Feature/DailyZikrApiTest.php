<?php

use App\Models\DailyZikr;
use App\Models\Zikir;
use App\Models\ZikirCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('daily zikir endpoint returns empty payload when manual daily zikir is not selected', function () {
    $date = '2026-04-10';

    $response = $this->getJson(route('api.v1.daily-zikr.show', ['date' => $date, 'locale' => 'tr']));

    $response
        ->assertOk()
        ->assertJsonPath('data', null)
        ->assertJsonPath('message', 'No daily zikr selected for this date.');
});

test('daily zikir endpoint validates date format', function () {
    $response = $this->getJson(route('api.v1.daily-zikr.show', ['date' => '10-04-2026']));

    $response
        ->assertStatus(422)
        ->assertJsonValidationErrors(['date']);
});

test('daily zikir endpoint uses manual daily content when available', function () {
    $activeCategory = ZikirCategory::query()->create([
        'name' => 'Temel Zikirler',
        'description' => 'Aktif kategori',
        'is_active' => true,
    ]);

    $zikir = Zikir::query()->create([
        'zikir_category_id' => $activeCategory->id,
        'zikir' => 'La ilahe illallah',
        'anlami' => 'Allahtan baska ilah yoktur.',
        'fazileti' => 'Tevhidi guclendirir.',
        'hedef' => 100,
    ]);

    DailyZikr::query()->create([
        'date' => '2026-04-11',
        'locale' => 'tr',
        'title' => 'Ozel gunun zikri',
        'zikir_id' => $zikir->id,
        'count_suggestion' => 111,
        'share_text' => 'Ozel paylasim metni',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.v1.daily-zikr.show', ['date' => '2026-04-11', 'locale' => 'tr']));

    $response
        ->assertOk()
        ->assertJsonPath('data.id', (int) $zikir->id)
        ->assertJsonPath('data.title', 'Ozel gunun zikri')
        ->assertJsonPath('data.count_suggestion', 111)
        ->assertJsonPath('data.share_text', 'Ozel paylasim metni')
        ->assertJsonPath('data.date', '2026-04-11');
});
