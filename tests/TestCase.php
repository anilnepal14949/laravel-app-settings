<?php

namespace ShreejaDigital\Settings\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use ShreejaDigital\Settings\SettingsServiceProvider;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [SettingsServiceProvider::class];
    }

    protected function defineEnvironment($app)
    {
        // Use array cache by default (no tags), your repo should fallback gracefully
        $app['config']->set('cache.default', env('CACHE_STORE', 'array'));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE', ':memory:'),
            'prefix'   => '',
        ]);

        // Generate a random encryption key at runtime (no secrets in VCS)
        $key = 'base64:'.base64_encode(random_bytes(32));
        $app['config']->set('app.key', $key);
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Run package migration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish config into test runtime
        $this->app['config']->set('settings', require __DIR__ . '/../config/settings.php');

        // Ensure table exists
        if (! Schema::hasTable('settings')) {
            $this->artisan('migrate')->run();
        }
    }
}
