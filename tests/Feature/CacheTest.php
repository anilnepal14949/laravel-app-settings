<?php

use ShreejaDigital\Settings\Facades\Settings;

it('uses cache and refreshes after set', function () {
    // First load warms cache
    Settings::set('cache.demo', 'one');
    expect(Settings::get('cache.demo'))->toBe('one');

    // Update should clear cache internally
    Settings::set('cache.demo', 'two');
    expect(Settings::get('cache.demo'))->toBe('two');
});
