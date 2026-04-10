<?php

use App\Models\Dua;
use App\Models\DuaCategory;
use App\Models\Zikir;
use App\Models\ZikirCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('zikir content endpoint returns active categories and mapped items', function () {
    $activeCategory = ZikirCategory::query()->create([
        'name' => 'Temel Zikirler',
        'description' => 'Açıklama',
        'is_active' => true,
    ]);

    Zikir::query()->create([
        'zikir_category_id' => $activeCategory->id,
        'zikir' => 'Sübhanallah',
        'anlami' => "Allah'ı tesbih ederim.",
        'fazileti' => 'Fazilet metni',
        'hedef' => 33,
    ]);

    $inactiveCategory = ZikirCategory::query()->create([
        'name' => 'Pasif Kategori',
        'description' => null,
        'is_active' => false,
    ]);

    Zikir::query()->create([
        'zikir_category_id' => $inactiveCategory->id,
        'zikir' => 'Pasif Zikir',
        'anlami' => 'Pasif',
        'fazileti' => 'Pasif',
        'hedef' => 99,
    ]);

    $response = $this->getJson(route('api.v1.content.zikirs'));

    $response
        ->assertOk()
        ->assertJsonPath('module', 'zikir')
        ->assertJsonPath('data.0.kategori_adi', 'Temel Zikirler')
        ->assertJsonPath('data.0.kategori_aciklama', 'Açıklama')
        ->assertJsonPath('data.0.zikirler.0.zikir', 'Sübhanallah')
        ->assertJsonPath('data.0.zikirler.0.adet', 33);

    expect($response->json('data'))->toHaveCount(1);
});

test('dua content endpoint returns active categories and active duas', function () {
    $activeCategory = DuaCategory::query()->create([
        'name' => 'Günlük Dualar',
        'is_active' => true,
    ]);

    Dua::query()->create([
        'dua_category_id' => $activeCategory->id,
        'source' => 'Hadis',
        'dua' => 'Allahümme...',
        'turkce_meali' => 'Allahım...',
        'is_active' => true,
    ]);

    Dua::query()->create([
        'dua_category_id' => $activeCategory->id,
        'source' => 'Hadis',
        'dua' => 'Pasif dua',
        'turkce_meali' => 'Pasif meal',
        'is_active' => false,
    ]);

    $inactiveCategory = DuaCategory::query()->create([
        'name' => 'Pasif Dua Kategorisi',
        'is_active' => false,
    ]);

    Dua::query()->create([
        'dua_category_id' => $inactiveCategory->id,
        'source' => 'Kitap',
        'dua' => 'Görünmemeli',
        'turkce_meali' => 'Görünmemeli',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.v1.content.duas'));

    $response
        ->assertOk()
        ->assertJsonPath('module', 'dua')
        ->assertJsonPath('data.0.kategori', 'Günlük Dualar')
        ->assertJsonPath('data.0.dualar.0.dua', 'Allahümme...')
        ->assertJsonPath('data.0.dualar.0.anlami', 'Allahım...')
        ->assertJsonPath('data.0.dualar.0.kaynak', 'Hadis');

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.dualar'))->toHaveCount(1);
});
