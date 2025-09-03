<?php

namespace ShreejaDigital\Settings\Commands;

use Illuminate\Console\Command;
use ShreejaDigital\Settings\Facades\Settings;
use Illuminate\Support\Facades\File;

class ExportCommand extends Command
{
    protected $signature = 'settings:export {--path=} {--pretty}';
    protected $description = 'Export all settings as JSON (to file or stdout)';

    public function handle(): int
    {
        $data = Settings::export();
        $json = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | ($this->option('pretty') ? JSON_PRETTY_PRINT : 0)
        );

        $path = $this->option('path');

        if ($path) {
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $json);
            $this->info("Exported settings to: {$path}");
        } else {
            $this->line($json);
        }

        return self::SUCCESS;
    }
}
