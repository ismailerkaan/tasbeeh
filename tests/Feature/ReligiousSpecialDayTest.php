<?php

use App\Models\ReligiousSpecialDay;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can create a religious special day with recommendations', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('admin.religious-special-days.store'), [
            'title' => 'Kadir Gecesi',
            'category' => 'kandil_geceleri',
            'event_date' => '2026-03-15',
            'hijri_date' => '27 Ramazan 1447',
            'short_description' => 'Bin aydan hayırlı gece.',
            'description' => 'Kadir Gecesi Kur’an’ın indirilmeye başlandığı mübarek gecedir.',
            'recommendations_text' => "Kur'an-ı Kerim okumak\nDua ve istiğfar etmek\nSadaka vermek",
            'is_active' => true,
        ])
        ->assertRedirect(route('admin.religious-special-days.index'));

    $day = ReligiousSpecialDay::query()->firstOrFail();
    expect($day->recommendations)->toBe([
        "Kur'an-ı Kerim okumak",
        'Dua ve istiğfar etmek',
        'Sadaka vermek',
    ]);
});

test('admin can update and delete a religious special day', function () {
    $day = ReligiousSpecialDay::query()->create([
        'title' => 'Eski Başlık',
        'category' => 'mubarek_gunler_aylar',
        'event_date' => '2026-03-15',
        'description' => 'Açıklama',
        'recommendations' => ['Dua etmek'],
        'is_active' => true,
    ]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.religious-special-days.update', $day), [
            'title' => 'Yeni Başlık',
            'category' => 'dini_bayramlar',
            'event_date' => '2026-03-16',
            'description' => 'Yeni açıklama',
            'recommendations_text' => "Dua etmek\nKur'an okumak",
            'is_active' => false,
        ])
        ->assertRedirect(route('admin.religious-special-days.index'));

    $this->assertDatabaseHas('religious_special_days', ['id' => $day->id, 'title' => 'Yeni Başlık', 'is_active' => false]);

    $this->actingAs($user)->delete(route('admin.religious-special-days.destroy', $day))->assertRedirect();
    $this->assertDatabaseMissing('religious_special_days', ['id' => $day->id]);
});

test('mobile api returns only active religious special days with details', function () {
    ReligiousSpecialDay::query()->create([
        'title' => 'Kadir Gecesi',
        'category' => 'kandil_geceleri',
        'event_date' => '2026-03-15',
        'hijri_date' => '27 Ramazan 1447',
        'short_description' => 'Bin aydan hayırlı gece.',
        'description' => 'Günün detaylı açıklaması.',
        'recommendations' => ['Dua etmek', 'Kur’an okumak'],
        'is_active' => true,
    ]);
    ReligiousSpecialDay::query()->create([
        'title' => 'Pasif Gün',
        'category' => 'mubarek_gunler_aylar',
        'event_date' => '2026-01-01',
        'description' => 'Gösterilmemeli.',
        'recommendations' => ['Gösterilmemeli'],
        'is_active' => false,
    ]);

    $this->getJson('/api/v1/religious-special-days')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Kadir Gecesi')
        ->assertJsonPath('data.0.category_label', 'Kandil Geceleri')
        ->assertJsonPath('data.0.category_color', '#8B5CF6')
        ->assertJsonPath('data.0.hijri_date', '27 Ramazan 1447')
        ->assertJsonPath('data.0.recommendations.1', 'Kur’an okumak');
});

test('religious special day requires a date description and recommendations', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('admin.religious-special-days.store'), ['title' => 'Eksik'])
        ->assertSessionHasErrors(['event_date', 'description', 'recommendations_text']);
});
