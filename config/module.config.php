<?php
return [
    'controller_plugins' => [
        'factories' => [
            'phpAbIsActive' => 'PhpAbModule\\Controller\\Plugin\\Service\\IsActiveFactory',
        ],
    ],
    'phpab' => [
        'default_filter' => 'phpab.default_filter',
        'default_variant_chooser' => 'phpab.default_variant_chooser',
        'storage' => 'runtime',
        'storage_options' => [],
        'tests' => [],
    ],
    'service_manager' => [
        'factories' => [
            'phpab.default_filter' => 'PhpAbModule\\Service\\DefaultFilterFactory',
            'phpab.default_variant_chooser' => 'PhpAbModule\\Service\\DefaultVariantChooserFactory',
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

