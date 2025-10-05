<?php

use danog\MadelineProto\Logger;

return [
    // THE FIX: Change the session path to a non-shared directory
    'session' => [
        'path' => env('MADELINE_PROTO_SESSION_FILE', '/tmp/session.madeline'),
    ],

    'settings' => [
        'app_info' => [
            'api_id' => env('TELEGRAM_API_ID'),
            'api_hash' => env('TELEGRAM_API_HASH'),
        ],
        'logger' => [
            'type' => Logger::FILE_LOGGER,
            'param' => storage_path('logs/madeline.log'), // Logs can stay in storage
            'level' => Logger::NOTICE,
        ],
    ],
];
