<?php

return [
    'default' => env('LOG_CHANNEL', 'stdout'),

    'channels' => [
        'stdout' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stdout',
                'level' => 'info'
            ]
        ],
    ],
];