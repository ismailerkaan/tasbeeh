<?php

use App\Models\ContentVersion;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can bump content version for a module', function () {
    $response = $this->post(route('admin.content-versions.bump'), [
        'module' => 'dua',
    ]);

    $response
        ->assertRedirect(route('admin.content-versions.index'))
        ->assertSessionHas('status');

    expect(ContentVersion::current()->dua_version)->toBe(2);
});

test('bump endpoint validates module input', function () {
    $response = $this->post(route('admin.content-versions.bump'), [
        'module' => 'invalid-module',
    ]);

    $response
        ->assertSessionHasErrors('module');
});
