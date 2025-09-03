<?php

use ShreejaDigital\Settings\Facades\Settings;

if (! function_exists('settings')) {
    /**
     * Get or set settings.
     *
     * settings('site.name');                 // get
     * settings(['site.name' => 'My Site']);  // set
     */
    function settings(string|array $key, mixed $default = null): mixed {
        if (is_array($key)) {
            foreach ($key as $k => $v) Settings::set($k, $v);
            return true;
        }
        return Settings::get($key, $default);
    }
}
