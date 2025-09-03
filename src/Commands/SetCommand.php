<?php

namespace ShreejaDigital\Settings\Commands;

use Illuminate\Console\Command;
use ShreejaDigital\Settings\Facades\Settings;

class SetCommand extends Command
{
    protected $signature = 'settings:set {key} {value}';
    protected $description = 'Set a setting value';

    public function handle(): int
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        // Try JSON decode to allow arrays/objects
        $decoded = json_decode($value, true);
        $value = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $value;

        Settings::set($key, $value);
        $this->info("Saved [$key].");
        return self::SUCCESS;
    }
}
