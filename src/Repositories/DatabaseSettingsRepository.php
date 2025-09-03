<?php

namespace ShreejaDigital\Settings\Repositories;

use ShreejaDigital\Settings\Contracts\SettingsRepository;
use ShreejaDigital\Settings\Models\Setting;
use ShreejaDigital\Settings\Events\SettingSaved;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;

class DatabaseSettingsRepository implements SettingsRepository
{
    protected string $cacheKey = 'settings:all';

    protected function cacheStore()
    {
        $useTags = Config::get('settings.use_cache_tags', true);
        $store = Cache::store(); // default
        return $useTags && method_exists($store, 'tags') ? $store->tags(['settings']) : $store;
    }

    protected function loadAll(): array
    {
        $ttl = Config::get('settings.cache_ttl');
        return $this->cacheStore()->remember($this->cacheKey, $ttl, function () {
            return Setting::query()->get()
                ->mapWithKeys(function ($row) {
                    $val = $row->value;

                    if ($row->encrypted) {
                        $val = Crypt::decryptString($val);
                    }

                    // Try JSON decode; fallback to raw string
                    $decoded = json_decode($val, true);
                    $val = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $val;

                    return [$row->key => $val];
                })
                ->toArray();
        });
    }

    protected function persist(string $key, mixed $value): void
    {
        $encryptedKeys = Config::get('settings.encrypted_keys', []);
        $shouldEncrypt = $this->matchesAny($key, $encryptedKeys);

        $isArrayOrObject = is_array($value) || is_object($value);
        $payload = $isArrayOrObject ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value;

        $storeValue = $shouldEncrypt ? Crypt::encryptString($payload) : $payload;

        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $storeValue, 'encrypted' => $shouldEncrypt]
        );

        event(new SettingSaved($key, $value));
    }

    protected function matchesAny(string $key, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            // simple wildcard support: services.*.secret
            $regex = '/^' . str_replace('\*', '.*', preg_quote($pattern, '/')) . '$/';
            if (preg_match($regex, $key)) return true;
        }
        return in_array($key, $patterns, true);
    }

    protected function applyCasts(string $key, mixed $value): mixed
    {
        $casts = Config::get('settings.casts', []);
        foreach ($casts as $pattern => $cast) {
            $regex = '/^' . str_replace('\*', '.*', preg_quote($pattern, '/')) . '$/';
            if (preg_match($regex, $key)) {
                return match ($cast) {
                    'int'   => (int) $value,
                    'float' => (float) $value,
                    'bool'  => filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? (bool) $value,
                    'array' => is_array($value) ? $value : (array) json_decode((string)$value, true),
                    default => $value,
                };
            }
        }
        return $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->loadAll();
        $value = Arr::get($all, $key, $default);
        return $this->applyCasts($key, $value);
    }

    public function set(string $key, mixed $value): void
    {
        $this->persist($key, $value);
        $this->clearCache();
    }

    public function forget(string $key): void
    {
        Setting::where('key', $key)->delete();
        $this->clearCache();
    }

    public function all(): array
    {
        return $this->loadAll();
    }

    public function import(array $data, bool $overwrite = true): void
    {
        foreach ($data as $key => $value) {
            if (!$overwrite) {
                $current = $this->get($key, null);
                if (!is_null($current)) continue;
            }
            $this->persist($key, $value);
        }
        $this->clearCache();
    }

    public function export(): array
    {
        return $this->all();
    }

    public function clearCache(): void
    {
        $this->cacheStore()->forget($this->cacheKey);
    }
}
