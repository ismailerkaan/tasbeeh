<?php

use App\Models\HadisCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

test('hadis categories index page is accessible from admin', function () {
    $response = $this->get(route('admin.hadis-categories.index'));

    $response
        ->assertOk()
        ->assertSee('Hadis Kategorileri')
        ->assertSee('Yeni Kategori');
});

test('admin can create hadis category', function () {
    $response = $this->post(route('admin.hadis-categories.store'), [
        'name' => 'Iman Hadisleri',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.hadis-categories.index'));

    $this->assertDatabaseHas('hadis_categories', [
        'name' => 'Iman Hadisleri',
        'is_active' => 1,
    ]);
});

test('admin can update hadis category', function () {
    $hadisCategory = HadisCategory::factory()->create([
        'name' => 'Eski Ad',
        'is_active' => true,
    ]);

    $response = $this->put(route('admin.hadis-categories.update', $hadisCategory), [
        'name' => 'Yeni Ad',
        'is_active' => '0',
    ]);

    $response->assertRedirect(route('admin.hadis-categories.index'));

    $this->assertDatabaseHas('hadis_categories', [
        'id' => $hadisCategory->id,
        'name' => 'Yeni Ad',
        'is_active' => 0,
    ]);
});

test('admin can delete hadis category', function () {
    $hadisCategory = HadisCategory::factory()->create();

    $response = $this->delete(route('admin.hadis-categories.destroy', $hadisCategory));

    $response->assertRedirect(route('admin.hadis-categories.index'));

    $this->assertDatabaseMissing('hadis_categories', [
        'id' => $hadisCategory->id,
    ]);
});
