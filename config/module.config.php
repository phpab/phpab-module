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
        'storage' => 'runtime',
        'tests' => [],
    ],
    'service_manager' => [
        'factories' => [
            'phpab.dispatcher' => 'PhpAbModule\\Service\\DispatcherFactory',
            'phpab.engine' => 'PhpAbModule\\Service\\EngineFactory',
            'phpab.participation_manager' => 'PhpAbModule\\Service\\ParticipationManagerFactory',
            'phpab.storage' => 'PhpAbModule\\Service\\StorageFactory',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'phpAbIsActive' => 'PhpAbModule\\View\\Helper\\Service\\IsActiveFactory',
        ],
    ],
];

