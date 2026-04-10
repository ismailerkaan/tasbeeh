<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin dashboard page is accessible', function () {
    $this->actingAs(User::factory()->create());

    $response = $this->get(route('admin.dashboard'));

    $response
        ->assertOk()
        ->assertSee('Toplam Kullanıcı')
        ->assertSee('7 Günlük Kullanıcı ve Senkron Trendleri')
        ->assertSee('En Çok Okunan 10 Zikir');
});
