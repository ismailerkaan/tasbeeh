<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('content version management page is accessible', function () {
    $response = $this->get(route('admin.content-versions.index'));

    $response
        ->assertOk()
        ->assertSee('Versiyon Yönetimi')
        ->assertSee('Yeni Zikir Yayınla')
        ->assertSee('Yeni Dua Yayınla')
        ->assertSee('Ezan Verisini Yayınla');
});
