<?php
return [
    'controller_plugins' => [
        'factories' => [
            'phpAbIsActive' => 'PhpAbModule\\Controller\\Plugin\\Service\\IsActiveFactory',
        ],
    ],
    'phpab' => [
        'analytics' => [
            /**
             * The name of the service that collects analytics.
             */
            'collector' => 'phpab.default_analytics_collector',

            /**
             * The name of the service that handles analytics after collecting.
             * You can set this to null to ignore analytics.
             */
            'handler' => 'phpab.default_analytics_handler',
        ],

        /**
         * The name of the service that loads the default filter for tests.
         * You can set this to null to ignore the default filter.
         */
        'default_filter' => 'phpab.default_filter',

        /**
         * The name of the service that loads the default variant chooser for tests.
         * You can set this to null to ignore the default variant chooser.
         */
        'default_variant_chooser' => 'phpab.default_variant_chooser',

        /**
         * The name of the storage that should be loaded.
         * For a list with options go to the documentation: https://phpab.github.io/
         */
        'storage' => 'runtime',

        /**
         * An array with options for the storage providers.
         * The options are defined in the documentation: https://phpab.github.io/
         */
        'storage_options' => [],

        /**
         * An array with tests, how to define these tests is defined in the documentation: https://phpab.github.io/
         */
        'tests' => [],
    ],
    'service_manager' => [
        'factories' => [
            'phpab.default_analytics_handler' => 'PhpAbModule\\Service\\DefaultAnalyticsHandlerFactory',
            'phpab.default_filter' => 'PhpAbModule\\Service\\DefaultFilterFactory',
            'phpab.default_variant_chooser' => 'PhpAbModule\\Service\\DefaultVariantChooserFactory',
            'phpab.dispatcher' => 'PhpAbModule\\Service\\DispatcherFactory',
            'phpab.engine' => 'PhpAbModule\\Service\\EngineFactory',
            'phpab.participation_manager' => 'PhpAbModule\\Service\\ParticipationManagerFactory',
            'phpab.storage' => 'PhpAbModule\\Service\\StorageFactory',
        ],
        'invokables' => [
            'phpab.default_analytics_collector' => 'PhpAb\\Analytics\\DataCollector\\\Generic',
            'phpab.analytics_data_collector' => 'PhpAb\Analytics\Google\DataCollector',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'phpAbIsActive' => 'PhpAbModule\\View\\Helper\\Service\\IsActiveFactory',
            'phpAbScript' => 'PhpAbModule\\View\\Helper\\Service\\ScriptFactory',
        ],
    ],
];

