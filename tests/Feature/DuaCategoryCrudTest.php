<?php

use App\Models\DuaCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

test('dua categories index page is accessible from admin', function () {
    $response = $this->get(route('admin.dua-categories.index'));

    $response
        ->assertOk()
        ->assertSee('Dua Kategorileri')
        ->assertSee('Yeni Kategori');
});

test('admin can create dua category', function () {
    $response = $this->post(route('admin.dua-categories.store'), [
        'name' => 'Sabah Dualari',
        'sort_order' => '10',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.dua-categories.index'));

    $this->assertDatabaseHas('dua_categories', [
        'name' => 'Sabah Dualari',
        'sort_order' => 10,
        'is_active' => 1,
    ]);
});

test('admin can update dua category', function () {
    $duaCategory = DuaCategory::factory()->create([
        'name' => 'Eski Ad',
        'is_active' => true,
    ]);

    $response = $this->put(route('admin.dua-categories.update', $duaCategory), [
        'name' => 'Yeni Ad',
        'sort_order' => '5',
        'is_active' => '0',
    ]);

    $response->assertRedirect(route('admin.dua-categories.index'));

    $this->assertDatabaseHas('dua_categories', [
        'id' => $duaCategory->id,
        'name' => 'Yeni Ad',
        'sort_order' => 5,
        'is_active' => 0,
    ]);
});

test('admin can delete dua category', function () {
    $duaCategory = DuaCategory::factory()->create();

    $response = $this->delete(route('admin.dua-categories.destroy', $duaCategory));

    $response->assertRedirect(route('admin.dua-categories.index'));

    $this->assertDatabaseMissing('dua_categories', [
        'id' => $duaCategory->id,
    ]);
});
