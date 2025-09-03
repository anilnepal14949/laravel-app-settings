<?php

namespace ShreejaDigital\Settings;

use Illuminate\Support\ServiceProvider;
use ShreejaDigital\Settings\Contracts\SettingsRepository;
use ShreejaDigital\Settings\Repositories\DatabaseSettingsRepository;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/settings.php', 'settings');

        $this->app->singleton(SettingsRepository::class, fn() => new DatabaseSettingsRepository());
        $this->app->alias(SettingsRepository::class, 'settings.repository');
    }

    public function boot(): void
    {
        // Publish
        $this->publishes([
            __DIR__.'/../config/settings.php' => config_path('settings.php'),
        ], 'settings-config');

        if (! class_exists('CreateSettingsTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/2025_09_03_000000_create_settings_table.php' =>
                    database_path('migrations/'.date('Y_m_d_His').'_create_settings_table.php'),
            ], 'settings-migrations');
        }

        // Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \ShreejaDigital\Settings\Commands\SetCommand::class,
                \ShreejaDigital\Settings\Commands\GetCommand::class,
                \ShreejaDigital\Settings\Commands\ForgetCommand::class,
                \ShreejaDigital\Settings\Commands\ExportCommand::class,
                \ShreejaDigital\Settings\Commands\ImportCommand::class,
            ]);
        }
    }
}
