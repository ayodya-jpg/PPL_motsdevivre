<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],

    // ⚠️ PERBAIKAN DISINI: Izinkan port frontend kamu (8091)
    'allowed_origins' => ['http://localhost:8091', '*'],

    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
