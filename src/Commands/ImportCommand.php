<?php

namespace ShreejaDigital\Settings\Commands;

use Illuminate\Console\Command;
use ShreejaDigital\Settings\Facades\Settings;
use Illuminate\Support\Facades\File;

class ImportCommand extends Command
{
    protected $signature = 'settings:import {file} {--no-overwrite}';
    protected $description = 'Import settings from a JSON file';

    public function handle(): int
    {
        $file = $this->argument('file');

        if (! File::exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $raw = File::get($file);
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON: ' . json_last_error_msg());
            return self::FAILURE;
        }

        $overwrite = ! $this->option('no-overwrite');
        Settings::import($data, $overwrite);

        $this->info("Imported settings from: {$file}" . ($overwrite ? ' (overwrite on)' : ' (no-overwrite)'));

        return self::SUCCESS;
    }
}
