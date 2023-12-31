<?php

declare(strict_types=1);

return [
    'logger' => [
        'pdf' => true,
        'log' => true,
        'api' => false,
    ],
    'notifications' => [
        'enabled' => true,
        'attachments' => [
            'stack_trace' => true
        ],
        'notifiable' => [
            'to' => 'oskars@noker.lv',
            'cc' => [],
            'bcc' => [],
        ],
    ]
];
