<?php

namespace ShreejaDigital\Settings\Commands;

use Illuminate\Console\Command;
use ShreejaDigital\Settings\Facades\Settings;

class GetCommand extends Command
{
    protected $signature = 'settings:get {key} {--default=} {--json}';
    protected $description = 'Get a setting value';

    public function handle(): int
    {
        $key = $this->argument('key');
        $default = $this->option('default');

        $value = Settings::get($key, $default);

        if ($this->option('json')) {
            $this->line(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        } else {
            if (is_array($value) || is_object($value)) {
                $this->line(json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            } else {
                $this->line((string) $value);
            }
        }

        return self::SUCCESS;
    }
}