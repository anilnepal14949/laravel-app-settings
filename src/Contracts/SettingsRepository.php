<?php

namespace ShreejaDigital\Settings\Contracts;

interface SettingsRepository
{
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value): void;
    public function forget(string $key): void;
    public function all(): array;
    public function import(array $data, bool $overwrite = true): void;
    public function export(): array;
    public function clearCache(): void;
}
