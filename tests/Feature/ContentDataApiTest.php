<?php

use App\Models\Dua;
use App\Models\DuaCategory;
use App\Models\Hadis;
use App\Models\HadisCategory;
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

test('hadis content endpoint returns active categories and active hadises', function () {
    $activeCategory = HadisCategory::query()->create([
        'name' => 'Ahlak Hadisleri',
        'is_active' => true,
    ]);

    Hadis::query()->create([
        'hadis_category_id' => $activeCategory->id,
        'source' => 'Sahih Buhari',
        'hadis' => 'Kolaylaştırınız, zorlaştırmayınız.',
        'turkce_meali' => 'Insanlara kolaylik gosterin.',
        'is_active' => true,
    ]);

    Hadis::query()->create([
        'hadis_category_id' => $activeCategory->id,
        'source' => 'Sahih Muslim',
        'hadis' => 'Pasif hadis',
        'turkce_meali' => 'Pasif meal',
        'is_active' => false,
    ]);

    $inactiveCategory = HadisCategory::query()->create([
        'name' => 'Pasif Hadis Kategorisi',
        'is_active' => false,
    ]);

    Hadis::query()->create([
        'hadis_category_id' => $inactiveCategory->id,
        'source' => 'Kaynak',
        'hadis' => 'Gorunmemeli',
        'turkce_meali' => 'Gorunmemeli',
        'is_active' => true,
    ]);

    $response = $this->getJson(route('api.v1.content.hadises'));

    $response
        ->assertOk()
        ->assertJsonPath('module', 'hadis')
        ->assertJsonPath('data.0.kategori', 'Ahlak Hadisleri')
        ->assertJsonPath('data.0.hadisler.0.hadis', 'Kolaylaştırınız, zorlaştırmayınız.')
        ->assertJsonPath('data.0.hadisler.0.anlami', 'Insanlara kolaylik gosterin.')
        ->assertJsonPath('data.0.hadisler.0.kaynak', 'Sahih Buhari');

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.hadisler'))->toHaveCount(1);
});
