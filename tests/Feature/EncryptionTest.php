<?php

use ShreejaDigital\Settings\Facades\Settings;
use ShreejaDigital\Settings\Models\Setting;

it('encrypts configured keys at rest and decrypts on read', function () {
    config()->set('settings.encrypted_keys', ['secrets.api_key']);

    Settings::set('secrets.api_key', 'super-secret');

    $row = Setting::where('key', 'secrets.api_key')->firstOrFail();
    expect($row->encrypted)->toBeTrue();
    expect($row->value)->not()->toBe('super-secret'); // ciphertext

    // read back
    expect(Settings::get('secrets.api_key'))->toBe('super-secret');
});
