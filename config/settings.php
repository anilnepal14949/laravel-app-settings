<?php

return [
    // Cache TTL in seconds (null = forever)
    'cache_ttl' => null,

    // Use cache tags if supported by your cache store
    'use_cache_tags' => false,

    // Keys to encrypt at rest (e.g. API secrets)
    'encrypted_keys' => [
        // 'services.mailgun.secret',
    ],

    // Default casts per key (simple dot keys or wildcard segments allowed)
    'casts' => [
        // 'site.enabled' => 'bool',
        // 'ui.colors' => 'array',
    ],
];
