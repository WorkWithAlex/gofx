<?php

return [

    // Whether DB logging is enabled
    'enabled' => env('DBLOG_ENABLED', true),

    // Use queue for writes
    'use_queue' => env('DBLOG_USE_QUEUE', true),

    // Queue connection / queue name (job will use default connection if null)
    'queue_connection' => env('DBLOG_QUEUE_CONNECTION', null),
    'queue_name' => env('DBLOG_QUEUE', 'default'),

    // Mask these keys (case-insensitive) anywhere inside the context/payload/headers
    'mask_keys' => [
        'password',
        'password_confirmation',
        'authorization',
        'auth',
        'api_token',
        'access_token',
        'token',
        'credit_card',
        'card_number',
        'card_cvv',
        'ssn',
        'email', // optional; you may remove if you want raw emails
    ],

    // Mask replacement
    'mask_with' => '*****',

    // Retention days for prune command
    'retention_days' => env('DBLOG_RETENTION_DAYS', 30),

    // Environments where automatic exception capture is enabled (empty = all)
    'capture_env' => env('DBLOG_CAPTURE_ENV', null), // e.g. 'production,staging' or null for all

];
