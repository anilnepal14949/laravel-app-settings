<?php

use Illuminate\Support\Facades\Event;
use ShreejaDigital\Settings\Events\SettingSaved;
use ShreejaDigital\Settings\Facades\Settings;

it('fires SettingSaved on set', function () {
    Event::fake([SettingSaved::class]);

    Settings::set('events.demo', 'ok');

    Event::assertDispatched(SettingSaved::class, function ($e) {
        return $e->key === 'events.demo' && $e->value === 'ok';
    });
});
