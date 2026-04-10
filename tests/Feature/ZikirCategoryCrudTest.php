<?php

use App\Models\ZikirCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('zikir categories index page is accessible from admin', function () {
    $response = $this->get(route('admin.zikir-categories.index'));

    $response
        ->assertOk()
        ->assertSee('Zikir Kategorileri')
        ->assertSee('Yeni Kategori');
});

test('admin can create zikir category', function () {
    $response = $this->post(route('admin.zikir-categories.store'), [
        'name' => 'Sabah Zikirleri',
        'description' => 'Sabah okunacak zikirler',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.zikir-categories.index'));

    $this->assertDatabaseHas('zikir_categories', [
        'name' => 'Sabah Zikirleri',
        'description' => 'Sabah okunacak zikirler',
        'is_active' => 1,
    ]);
});

test('admin can update zikir category', function () {
    $zikirCategory = ZikirCategory::factory()->create([
        'name' => 'Eski Ad',
        'is_active' => true,
    ]);

    $response = $this->put(route('admin.zikir-categories.update', $zikirCategory), [
        'name' => 'Yeni Ad',
        'description' => 'Guncel aciklama',
        'is_active' => '0',
    ]);

    $response->assertRedirect(route('admin.zikir-categories.index'));

    $this->assertDatabaseHas('zikir_categories', [
        'id' => $zikirCategory->id,
        'name' => 'Yeni Ad',
        'description' => 'Guncel aciklama',
        'is_active' => 0,
    ]);
});

test('admin can delete zikir category', function () {
    $zikirCategory = ZikirCategory::factory()->create();

    $response = $this->delete(route('admin.zikir-categories.destroy', $zikirCategory));

    $response->assertRedirect(route('admin.zikir-categories.index'));

    $this->assertDatabaseMissing('zikir_categories', [
        'id' => $zikirCategory->id,
    ]);
});
