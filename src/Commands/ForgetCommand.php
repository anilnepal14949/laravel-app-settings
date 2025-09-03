<?php

namespace ShreejaDigital\Settings\Commands;

use Illuminate\Console\Command;
use ShreejaDigital\Settings\Facades\Settings;

class ForgetCommand extends Command
{
    protected $signature = 'settings:forget {key}';
    protected $description = 'Delete a setting';

    public function handle(): int
    {
        $key = $this->argument('key');

        Settings::forget($key);
        $this->info("Deleted [$key].");

        return self::SUCCESS;
    }
}
