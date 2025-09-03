<?php

use ShreejaDigital\Settings\Facades\Settings;

it('artisan set/get/forget work', function () {
    $this->artisan('settings:set', ['key' => 'cli.name', 'value' => 'CLI App'])
        ->assertSuccessful();

    $this->artisan('settings:get', ['key' => 'cli.name'])
        ->expectsOutput('CLI App')
        ->assertSuccessful();

    $this->artisan('settings:forget', ['key' => 'cli.name'])
        ->assertSuccessful();

    expect(Settings::get('cli.name'))->toBeNull();
});

it('artisan export/import work', function () {
    $path = base_path('tests/tmp/settings.json');
    @mkdir(dirname($path), 0777, true);

    settings(['ex.a' => 123, 'ex.b' => ['x' => 'y']]);

    $this->artisan('settings:export', ['--path' => $path, '--pretty' => true])
        ->assertSuccessful();

    // clear and re-import
    Settings::forget('ex.a');
    Settings::forget('ex.b');

    $this->artisan('settings:import', ['file' => $path, '--no-overwrite' => false])
        ->assertSuccessful();

    expect(Settings::get('ex.a'))->toBe(123);
    expect(Settings::get('ex.b')['x'])->toBe('y');
});
