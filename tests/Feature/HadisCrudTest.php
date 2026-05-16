<?php

use App\Models\Hadis;
use App\Models\HadisCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

test('hadis index page is accessible', function () {
    $response = $this->get(route('admin.hadises.index'));

    $response
        ->assertOk()
        ->assertSee('Hadisler')
        ->assertSee('Yeni Hadis');
});

test('admin can create hadis', function () {
    $category = HadisCategory::factory()->create([
        'is_active' => true,
    ]);

    $response = $this->post(route('admin.hadises.store'), [
        'hadis_category_id' => $category->id,
        'source' => 'Riyazus Salihin',
        'hadis' => 'Ameller niyetlere göredir.',
        'turkce_meali' => 'Ameller niyetlere gore degerlendirilir.',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.hadises.index'));

    $this->assertDatabaseHas('hadises', [
        'hadis_category_id' => $category->id,
        'source' => 'Riyazus Salihin',
        'hadis' => 'Ameller niyetlere göredir.',
        'turkce_meali' => 'Ameller niyetlere gore degerlendirilir.',
        'is_active' => 1,
    ]);
});

test('admin can update hadis', function () {
    $oldCategory = HadisCategory::factory()->create(['is_active' => true]);
    $newCategory = HadisCategory::factory()->create(['is_active' => true]);
    $hadis = Hadis::factory()->create([
        'hadis_category_id' => $oldCategory->id,
    ]);

    $response = $this->put(route('admin.hadises.update', $hadis), [
        'hadis_category_id' => $newCategory->id,
        'source' => 'Sahih Buhari',
        'hadis' => 'Mumin kardesinin aynasidir.',
        'turkce_meali' => 'Mumin muminin aynasidir.',
        'is_active' => '0',
    ]);

    $response->assertRedirect(route('admin.hadises.index'));

    $this->assertDatabaseHas('hadises', [
        'id' => $hadis->id,
        'hadis_category_id' => $newCategory->id,
        'source' => 'Sahih Buhari',
        'is_active' => 0,
    ]);
});

test('admin can delete hadis', function () {
    $hadis = Hadis::factory()->create();

    $response = $this->delete(route('admin.hadises.destroy', $hadis));

    $response->assertRedirect(route('admin.hadises.index'));

    $this->assertDatabaseMissing('hadises', [
        'id' => $hadis->id,
    ]);
});

test('hadis create validates required fields', function () {
    $response = $this->post(route('admin.hadises.store'), []);

    $response->assertSessionHasErrors([
        'hadis_category_id',
        'source',
        'hadis',
        'turkce_meali',
    ]);
});
