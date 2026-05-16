<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

test('content version management page is accessible', function () {
    $response = $this->get(route('admin.content-versions.index'));

    $response
        ->assertOk()
        ->assertSee('Versiyon Yönetimi')
        ->assertSee('Yeni Zikir Yayınla')
        ->assertSee('Yeni Dua Yayınla')
        ->assertSee('Yeni Hadis Yayınla')
        ->assertSee('Ezan Verisini Yayınla');
});
