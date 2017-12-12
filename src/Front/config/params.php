<?php

return [
    'adminEmail' => 'admin@example.com',
    'judger' => [
        'host' => 'localhost',
        'port' => 27015,
    ],
    'vmMultiplier' => 2,
    'uploadsDir' => substr(__DIR__, 0, strlen(__DIR__) - 7).'/uploads',
    'queryPerPage' => 20,
];
