<?php

use App\Models\Dua;
use App\Models\DuaCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dua index page is accessible', function () {
    $response = $this->get(route('admin.duas.index'));

    $response
        ->assertOk()
        ->assertSee('Dualar')
        ->assertSee('Yeni Dua');
});

test('admin can create dua', function () {
    $category = DuaCategory::factory()->create([
        'is_active' => true,
    ]);

    $response = $this->post(route('admin.duas.store'), [
        'dua_category_id' => $category->id,
        'source' => 'Riyazus Salihin',
        'dua' => 'Allahumme inni eseluke...',
        'turkce_meali' => 'Allahim senden isterim...',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.duas.index'));

    $this->assertDatabaseHas('duas', [
        'dua_category_id' => $category->id,
        'source' => 'Riyazus Salihin',
        'dua' => 'Allahumme inni eseluke...',
        'turkce_meali' => 'Allahim senden isterim...',
        'is_active' => 1,
    ]);
});

test('admin can update dua', function () {
    $oldCategory = DuaCategory::factory()->create(['is_active' => true]);
    $newCategory = DuaCategory::factory()->create(['is_active' => true]);
    $dua = Dua::factory()->create([
        'dua_category_id' => $oldCategory->id,
    ]);

    $response = $this->put(route('admin.duas.update', $dua), [
        'dua_category_id' => $newCategory->id,
        'source' => 'Kuran-i Kerim',
        'dua' => 'Rabbena atina...',
        'turkce_meali' => 'Rabbimiz bize ver...',
        'is_active' => '0',
    ]);

    $response->assertRedirect(route('admin.duas.index'));

    $this->assertDatabaseHas('duas', [
        'id' => $dua->id,
        'dua_category_id' => $newCategory->id,
        'source' => 'Kuran-i Kerim',
        'is_active' => 0,
    ]);
});

test('admin can delete dua', function () {
    $dua = Dua::factory()->create();

    $response = $this->delete(route('admin.duas.destroy', $dua));

    $response->assertRedirect(route('admin.duas.index'));

    $this->assertDatabaseMissing('duas', [
        'id' => $dua->id,
    ]);
});

test('dua create validates required fields', function () {
    $response = $this->post(route('admin.duas.store'), []);

    $response->assertSessionHasErrors([
        'dua_category_id',
        'source',
        'dua',
        'turkce_meali',
    ]);
});
