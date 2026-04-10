<?php

use App\Models\Zikir;
use App\Models\ZikirCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('zikir index page is accessible', function () {
    $response = $this->get(route('admin.zikirs.index'));

    $response
        ->assertOk()
        ->assertSee('Zikirler')
        ->assertSee('Yeni Zikir');
});

test('admin can create zikir', function () {
    $category = ZikirCategory::factory()->create([
        'is_active' => true,
    ]);

    $response = $this->post(route('admin.zikirs.store'), [
        'zikir_category_id' => $category->id,
        'zikir' => 'Subhanallah',
        'anlami' => 'Allah her turlu eksiklikten munezzehtir.',
        'fazileti' => 'Kalbi huzurla doldurur.',
        'hedef' => 100,
    ]);

    $response->assertRedirect(route('admin.zikirs.index'));

    $this->assertDatabaseHas('zikirs', [
        'zikir_category_id' => $category->id,
        'zikir' => 'Subhanallah',
        'anlami' => 'Allah her turlu eksiklikten munezzehtir.',
        'fazileti' => 'Kalbi huzurla doldurur.',
        'hedef' => 100,
    ]);
});

test('admin can update zikir', function () {
    $oldCategory = ZikirCategory::factory()->create(['is_active' => true]);
    $newCategory = ZikirCategory::factory()->create(['is_active' => true]);
    $zikir = Zikir::factory()->create([
        'zikir_category_id' => $oldCategory->id,
    ]);

    $response = $this->put(route('admin.zikirs.update', $zikir), [
        'zikir_category_id' => $newCategory->id,
        'zikir' => 'Elhamdulillah',
        'anlami' => 'Hamd alemlerin Rabbi olan Allahadir.',
        'fazileti' => 'Nimete sukur bilincini guclendirir.',
        'hedef' => 33,
    ]);

    $response->assertRedirect(route('admin.zikirs.index'));

    $this->assertDatabaseHas('zikirs', [
        'id' => $zikir->id,
        'zikir_category_id' => $newCategory->id,
        'zikir' => 'Elhamdulillah',
        'hedef' => 33,
    ]);
});

test('admin can delete zikir', function () {
    $zikir = Zikir::factory()->create();

    $response = $this->delete(route('admin.zikirs.destroy', $zikir));

    $response->assertRedirect(route('admin.zikirs.index'));

    $this->assertDatabaseMissing('zikirs', [
        'id' => $zikir->id,
    ]);
});

test('zikir create validates required fields', function () {
    $response = $this->post(route('admin.zikirs.store'), []);

    $response->assertSessionHasErrors([
        'zikir_category_id',
        'zikir',
        'anlami',
        'fazileti',
        'hedef',
    ]);
});
