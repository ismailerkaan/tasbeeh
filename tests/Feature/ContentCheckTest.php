<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('content check endpoint returns current versions', function () {
    $response = $this->getJson('/api/v1/content/check');

    $response
        ->assertOk()
        ->assertJsonPath('versions.zikir_version', 1)
        ->assertJsonPath('versions.dua_version', 1)
        ->assertJsonPath('versions.prayer_times_version', 1)
        ->assertJsonPath('has_updates', true)
        ->assertJsonPath('changed_modules.0', 'zikir')
        ->assertJsonPath('changed_modules.1', 'dua')
        ->assertJsonPath('changed_modules.2', 'prayer_times');
});

test('content check endpoint marks changed modules when client versions are behind', function () {
    $this->post(route('admin.content-versions.bump'), ['module' => 'zikir'])->assertRedirect();

    $response = $this->getJson('/api/v1/content/check?zikir_version=1&dua_version=1&prayer_times_version=1');

    $response
        ->assertOk()
        ->assertJsonPath('has_updates', true)
        ->assertJsonPath('changed_modules.0', 'zikir')
        ->assertJsonPath('versions.zikir_version', 2);
});
