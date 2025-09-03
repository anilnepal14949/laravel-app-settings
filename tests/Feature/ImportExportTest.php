<?php

use ShreejaDigital\Settings\Facades\Settings;

it('exports and imports settings', function () {
    Settings::set('x', ['k' => 'v']);
    $export = Settings::export();
    expect($export['x']['k'])->toBe('v');

    Settings::forget('x');
    expect(Settings::get('x'))->toBeNull();

    Settings::import($export, overwrite: false);
    expect(Settings::get('x')['k'])->toBe('v');
});
