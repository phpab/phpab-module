<?php
return [
    'controller_plugins' => [
        'factories' => [
            'phpAbIsActive' => 'PhpAbModule\\Controller\\Plugin\\Service\\IsActiveFactory',
        ],
    ],
    'phpab' => [
        'analytics' => null,
        'participation_strategy' => null,
        'storage' => null,
        'tests' => [],
    ],
    'service_manager' => [
        'factories' => [
            'PhpAbModule\\Engine' => 'PhpAbModule\\Service\\EngineFactory',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'phpAbIsActive' => 'PhpAbModule\\View\\Helper\\Service\\IsActiveFactory',
        ],
    ],
];

