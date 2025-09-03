# Laravel App Settings

A lightweight Laravel package for storing **dynamic, database-driven application settings**.  
Unlike `.env` or static `config/*.php` files, these settings can be changed **at runtime** by administrators or business users — no code deployments required.

---

## ✨ Features

- Store key-value settings in the database (with optional cache).
- Get and set settings easily via helper or Facade.
- Support for arrays, JSON, casts (`bool`, `int`, `array`, etc.).
- Encrypt sensitive keys at rest (API tokens, passwords).
- Artisan commands for managing settings (`set`, `get`, `forget`, `export`, `import`).
- Configurable cache TTL.
- Blade helper and optional directive.
- Ready for multi-tenant or admin-panel integration.

---

## 📦 Installation

Require the package via Composer:

```bash
composer require shreeja_digital/laravel-app-settings
```
---

## ⚙️ Publish & Migrate

```bash
php artisan vendor:publish --tag=settings-config
php artisan vendor:publish --tag=settings-migrations
php artisan migrate
```

---

## 🛠 Usage

### Helper

```php
// Get with default
$title = settings('site.name', 'My Website');

// Set single
settings(['site.name' => 'Shreeja Digital']);

// Store arrays/JSON
settings(['ui.colors' => ['primary' => '#fe5516', 'dark' => '#112335']]);
```

### Facade

```php
use Settings;

// Get
Settings::get('site.name');

// Set
Settings::set('site.name', 'My App');

// Forget
Settings::forget('site.name');

// All settings
Settings::all();
```

### Blade

```blade
{{ settings('site.name') }}
```

(Optional directive, if enabled in the service provider:)

```blade
@setting('site.name', 'Fallback Name')
```

---

## 🧑‍💻 Artisan Commands

```bash
# Set a value
php artisan settings:set site.name "My Website"

# Get a value
php artisan settings:get site.name

# Delete a setting
php artisan settings:forget site.name

# Export to JSON
php artisan settings:export --path=storage/app/settings.json --pretty

# Import from JSON
php artisan settings:import storage/app/settings.json --no-overwrite
```

---

## 🔒 Encryption

Mark keys as encrypted in `config/settings.php`:

```php
'encrypted_keys' => [
    'services.payment.secret',
    'mail.password',
],
```

Values will be encrypted before storage and decrypted on retrieval.

---

## 🔧 Config Options

`config/settings.php`:

```php
return [
    'cache_ttl' => null, // null = forever
    'use_cache_tags' => false, // set to true if cache store is configured
    'encrypted_keys' => [],
    'casts' => [
        // 'site.enabled' => 'bool',
        // 'ui.colors' => 'array',
    ],
];
```

---

## 🧪 Testing

This package is testable with [Orchestra Testbench](https://github.com/orchestral/testbench).

1. Install dev dependencies:

```bash
composer require --dev orchestra/testbench pestphp/pest pestphp/pest-plugin-laravel
```

2. Run tests:

```bash
./vendor/bin/pest
php artisan test
```

---

## ❓ Why not `.env` or `config/`?

- `.env` → server-level settings, not editable by app users.
- `config/*.php` → static arrays, cached, need redeploy to change.
- **This package** → runtime, DB-backed, user-editable settings with cache, export/import, encryption, and casting.

Perfect for **admin-editable site config**, **multi-tenant apps**, and **non-developer friendly control**.

---

## 📊 Badges (to enable after publishing to Packagist)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/shreeja_digital/laravel-app-settings.svg?style=flat-square)](https://packagist.org/packages/shreeja_digital/laravel-app-settings)
[![Total Downloads](https://img.shields.io/packagist/dt/shreeja_digital/laravel-app-settings.svg?style=flat-square)](https://packagist.org/packages/shreeja_digital/laravel-app-settings)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

---

## 📄 License
The MIT License (MIT). See [LICENSE.md](LICENSE.md) for details.

---
Happy Coding!
