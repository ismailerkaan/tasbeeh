<?php

use App\Models\ContentVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());
});

test('admin can bump content version for a module', function () {
    $response = $this->post(route('admin.content-versions.bump'), [
        'module' => 'dua',
    ]);

    $response
        ->assertRedirect(route('admin.content-versions.index'))
        ->assertSessionHas('status');

    expect(ContentVersion::current()->dua_version)->toBe(2);
});

test('admin can bump hadis content version for a module', function () {
    $response = $this->post(route('admin.content-versions.bump'), [
        'module' => 'hadis',
    ]);

    $response
        ->assertRedirect(route('admin.content-versions.index'))
        ->assertSessionHas('status');

    expect(ContentVersion::current()->hadis_version)->toBe(2);
});

test('bump endpoint validates module input', function () {
    $response = $this->post(route('admin.content-versions.bump'), [
        'module' => 'invalid-module',
    ]);

    $response
        ->assertSessionHasErrors('module');
});
