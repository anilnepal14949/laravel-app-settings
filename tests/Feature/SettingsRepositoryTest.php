<?php

use ShreejaDigital\Settings\Facades\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('sets and gets a scalar value', function () {
    Settings::set('site.name', 'My App');
    expect(Settings::get('site.name'))->toBe('My App');
});

it('stores and retrieves arrays (JSON)', function () {
    Settings::set('ui.colors', ['primary' => '#fe5516']);
    $val = Settings::get('ui.colors');
    expect($val)->toBeArray()->and($val['primary'])->toBe('#fe5516');
});

it('applies casts from config', function () {
    config()->set('settings.casts', ['site.enabled' => 'bool']);

    Settings::set('site.enabled', 'true'); // string in
    expect(Settings::get('site.enabled'))->toBeTrue();
});

it('forgets keys', function () {
    Settings::set('meta.tagline', 'Hello');
    Settings::forget('meta.tagline');
    expect(Settings::get('meta.tagline'))->toBeNull();
});

it('returns all as an array', function () {
    Settings::set('a', 1);
    Settings::set('b', 2);
    $all = Settings::all();
    expect($all)->toBeArray()->toHaveKeys(['a', 'b']);
});
