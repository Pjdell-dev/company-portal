<?php

return [

    // include web auth endpoints so preflight for /login and /logout succeed
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000', 
        'http://localhost:3001',
        'http://localhost:5173',    // Vite dev server
        'http://127.0.0.1:3000',    // React dev server
        'http://127.0.0.1:3001',    // Alternative localhost
        'http://127.0.0.1:5173',    // Vite dev server by IP
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,   // ← MUST be true for Sanctum
];