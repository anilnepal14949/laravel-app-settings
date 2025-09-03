<?php

namespace ShreejaDigital\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void set(string $key, mixed $value)
 * @method static void forget(string $key)
 * @method static array all()
 * @method static void import(array $data, bool $overwrite = true)
 * @method static array export()
 * @method static void clearCache()
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'settings.repository';
    }
}
