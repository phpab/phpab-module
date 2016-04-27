<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\Service;

use PhpAb\Engine\EngineInterface;
use PhpAb\Event\DispatcherInterface;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAb\Participation\PercentageFilter;
use PhpAb\Variant\CallbackVariant;
use PhpAb\Variant\RandomChooser;
use PhpAb\Variant\SimpleVariant;
use PhpAbModule\Service\EngineFactory;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\ServiceManager\ServiceManager;

class EngineFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockedServiceLocator($config = null, $withFilterAndVariantChooser = false)
    {
        if ($config === null) {
            $config = [
                'phpab' => [
                    'default_filter' => null,
                    'default_variant_chooser' => null,
                    'tests' => [],
                ],
            ];
        }

        $participationManagerMock = $this->getMockForAbstractClass(ParticipationManagerInterface::class);
        $dispatcherMock = $this->getMockForAbstractClass(DispatcherInterface::class);

        $serviceLocator = $this->getMock(ServiceManager::class);

        $serviceLocator
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('Config'))
            ->willReturn($config);

        $serviceLocator
            ->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('phpab.participation_manager'))
            ->willReturn($participationManagerMock);

        $serviceLocator
            ->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo('phpab.dispatcher'))
            ->willReturn($dispatcherMock);

        if ($withFilterAndVariantChooser) {
            $serviceLocator
                ->expects($this->at(3))
                ->method('get')
                ->with($this->equalTo('my_filter'))
                ->willReturn(new PercentageFilter(100));

            $serviceLocator
                ->expects($this->at(4))
                ->method('get')
                ->with($this->equalTo('my_variant_chooser'))
                ->willReturn(new RandomChooser());
        }

        return $serviceLocator;
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::createService
     */
    public function testWithEmptyTests()
    {
        // Arrange
        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($this->createMockedServiceLocator());

        // Assert
        $this->assertInstanceOf(EngineInterface::class, $engine);
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadService
     */
    public function testWithInvalidDefaultFilter()
    {
        // Arrange
        $config = [
            'phpab' => [
                'default_filter' => 'my_filter',
            ],
        ];

        // Assert
        $serviceLocator = $this->createMockedServiceLocator($config);
        $serviceLocator
            ->expects($this->at(3))
            ->method('has')
            ->with($this->equalTo('my_filter'))
            ->willReturn(false);

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($serviceLocator);
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadService
     */
    public function testWithInvalidDefaultVariantChooser()
    {
        // Arrange
        $config = [
            'phpab' => [
                'default_variant_chooser' => 'my_variant_chooser',
            ],
        ];

        // Assert
        $serviceLocator = $this->createMockedServiceLocator($config);
        $serviceLocator
            ->expects($this->at(3))
            ->method('has')
            ->with($this->equalTo('my_variant_chooser'))
            ->willReturn(false);

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($serviceLocator);
    }


    /**
     * @covers PhpAbModule\Service\EngineFactory::loadService
     */
    public function testWithDefaultFilter()
    {
        // Arrange
        $config = [
            'phpab' => [
                'default_filter' => 'my_filter',
            ],
        ];

        // Assert
        $serviceLocator = $this->createMockedServiceLocator($config);
        $serviceLocator
            ->expects($this->at(3))
            ->method('has')
            ->with($this->equalTo('my_filter'))
            ->willReturn(true);

        $serviceLocator
            ->expects($this->at(4))
            ->method('get')
            ->with($this->equalTo('my_filter'))
            ->willReturn(new PercentageFilter(100));

        $factory = new EngineFactory();

        // Act
        $factory->createService($serviceLocator);
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadService
     */
    public function testWithDefaultVariantChooser()
    {
        // Arrange
        $config = [
            'phpab' => [
                'default_variant_chooser' => 'my_variant_chooser',
            ],
        ];

        // Assert
        $serviceLocator = $this->createMockedServiceLocator($config);
        $serviceLocator
            ->expects($this->at(3))
            ->method('has')
            ->with($this->equalTo('my_variant_chooser'))
            ->willReturn(true);

        $serviceLocator
            ->expects($this->at(4))
            ->method('get')
            ->with($this->equalTo('my_variant_chooser'))
            ->willReturn(new RandomChooser());

        $factory = new EngineFactory();

        // Act
        $factory->createService($serviceLocator);
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTests
     */
    public function testWithoutTestsConfig()
    {
        // Arrange
        $config = [
            'phpab' => [
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($this->createMockedServiceLocator($config));

        // Assert
        $this->assertCount(0, $engine->getTests());
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTests
     * @covers PhpAbModule\Service\EngineFactory::loadTest
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage There must be at least one filter in the Engine or in the TestBag
     */
    public function testTestWithoutConfig()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTests
     * @covers PhpAbModule\Service\EngineFactory::loadTest
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     */
    public function testWithSingleTest()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($this->createMockedServiceLocator($config, true));

        // Assert
        $this->assertCount(1, $engine->getTests());
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     */
    public function testWithStringVariant()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => 'simple',
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($this->createMockedServiceLocator($config, true));

        // Assert
        $this->assertInstanceOf(SimpleVariant::class, $engine->getTest('my-test')->getVariant('my-variant'));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @expectedException RuntimeException
     * @expectedExceptionMessage The type of the variant is missing.
     */
    public function testWithNoVariantType()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config, true));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantSimple
     */
    public function testSimpleVariantWithOptions()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'simple',
                                'options' => [],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($this->createMockedServiceLocator($config, true));

        // Assert
        $this->assertInstanceOf(SimpleVariant::class, $engine->getTest('my-test')->getVariant('my-variant'));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantSimple
     */
    public function testSimpleVariantWithoutOptions()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'simple',
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($this->createMockedServiceLocator($config, true));

        // Assert
        $this->assertInstanceOf(SimpleVariant::class, $engine->getTest('my-test')->getVariant('my-variant'));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantCallback
     */
    public function testCallbackVariantWithOptions()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'callback',
                                'options' => [
                                    'callback' => function() {
                                    }
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($this->createMockedServiceLocator($config, true));

        // Assert
        $this->assertInstanceOf(CallbackVariant::class, $engine->getTest('my-test')->getVariant('my-variant'));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantCallback
     * @expectedException RuntimeException
     * @expectedExceptionMessage Missing "callback" for callback variant.
     */
    public function testCallbackVariantWithoutCallback()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'callback',
                                'options' => [
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config, true));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantCallback
     * @expectedException RuntimeException
     * @expectedExceptionMessage The "callback" for callback variant cannot be called.
     */
    public function testCallbackVariantWithoutCallableCallback()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'callback',
                                'options' => [
                                    'callback' => null,
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config, true));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantCallback
     * @expectedException RuntimeException
     * @expectedExceptionMessage Missing "callback" for callback variant.
     */
    public function testCallbackVariantWithoutOptions()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'callback',
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config, true));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantEventManager
     */
    public function testEventManagerVariantWithValidOptions()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'event_manager',
                                'options' => [
                                    'callback' => function() { },
                                    'event' => 'test',
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $eventManager = $this->getMockForAbstractClass(EventManagerInterface::class);

        $application = $this->getMockForAbstractClass(ApplicationInterface::class);
        $application->expects($this->once())->method('getEventManager')->willReturn($eventManager);

        $serviceLocator = $this->createMockedServiceLocator($config, true);
        $serviceLocator
            ->expects($this->at(5))
            ->method('get')
            ->with($this->equalTo('Application'))
            ->willReturn($application);

        $factory = new EngineFactory();

        // Act
        $factory->createService($serviceLocator);
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantEventManager
     * @expectedException RuntimeException
     * @expectedExceptionMessage Missing the "callback" option for event manager variant.
     */
    public function testEventManagerVariantWithoutOptions()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'event_manager',
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config, true));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantEventManager
     * @expectedException RuntimeException
     * @expectedExceptionMessage Missing the "callback" option for event manager variant.
     */
    public function testEventManagerVariantWithoutCallbackOption()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'event_manager',
                                'options' => [],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config, true));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantEventManager
     * @expectedException RuntimeException
     * @expectedExceptionMessage Missing the "event" option for event manager variant.
     */
    public function testEventManagerVariantWithoutEventOption()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'event_manager',
                                'options' => [
                                    'callback' => function() { },
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config, true));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantEventManager
     */
    public function testEventManagerVariantWithoutPriorityOption()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'event_manager',
                                'options' => [
                                    'callback' => function() { },
                                    'event' => 'dispatch',
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $eventManager = $this->getMockForAbstractClass(EventManagerInterface::class);

        $application = $this->getMockForAbstractClass(ApplicationInterface::class);
        $application->expects($this->once())->method('getEventManager')->willReturn($eventManager);

        $serviceLocator = $this->createMockedServiceLocator($config, true);
        $serviceLocator
            ->expects($this->at(5))
            ->method('get')
            ->with($this->equalTo('Application'))
            ->willReturn($application);

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($serviceLocator);

        // Assert
        $this->assertEquals(0, $engine->getTest('my-test')->getVariant('my-variant')->getPriority());
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantEventManager
     */
    public function testEventManagerVariantWithCustomEventManager()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'event_manager',
                                'options' => [
                                    'event_manager' => 'my_event_manager',
                                    'callback' => function() { },
                                    'event' => 'dispatch',
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $eventManager = $this->getMockForAbstractClass(EventManagerInterface::class);

        $serviceLocator = $this->createMockedServiceLocator($config, true);
        $serviceLocator
            ->expects($this->at(5))
            ->method('get')
            ->with($this->equalTo('my_event_manager'))
            ->willReturn($eventManager);

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($serviceLocator);

        // Assert
        $this->assertEquals($eventManager, $engine->getTest('my-test')->getVariant('my-variant')->getEventManager());
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantEventManager
     * @expectedException RuntimeException
     * @expectedExceptionMessage The callback is not callable.
     */
    public function testEventManagerVariantWithInvalidCallback()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'event_manager',
                                'options' => [
                                    'callback' => null,
                                    'event' => 'test',
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $eventManager = $this->getMockForAbstractClass(EventManagerInterface::class);

        $application = $this->getMockForAbstractClass(ApplicationInterface::class);
        $application->expects($this->once())->method('getEventManager')->willReturn($eventManager);

        $serviceLocator = $this->createMockedServiceLocator($config, true);
        $serviceLocator
            ->expects($this->at(5))
            ->method('get')
            ->with($this->equalTo('Application'))
            ->willReturn($application);

        $factory = new EngineFactory();

        // Act
        $factory->createService($serviceLocator);
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantEventManager
     */
    public function testEventManagerVariantWithServiceManagerCallback()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'event_manager',
                                'options' => [
                                    'callback' => 'my_callback',
                                    'event' => 'test',
                                ],
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $callback = function() { };

        $eventManager = $this->getMockForAbstractClass(EventManagerInterface::class);

        $application = $this->getMockForAbstractClass(ApplicationInterface::class);
        $application->expects($this->once())->method('getEventManager')->willReturn($eventManager);

        $serviceLocator = $this->createMockedServiceLocator($config, true);

        $serviceLocator
            ->expects($this->at(5))
            ->method('get')
            ->with($this->equalTo('Application'))
            ->willReturn($application);

        $serviceLocator
            ->expects($this->at(6))
            ->method('has')
            ->with($this->equalTo('my_callback'))
            ->willReturn(true);

        $serviceLocator
            ->expects($this->at(7))
            ->method('get')
            ->with($this->equalTo('my_callback'))
            ->willReturn($callback);

        $factory = new EngineFactory();

        // Act
        $engine = $factory->createService($serviceLocator);

        // Assert
        $this->assertEquals($callback, $engine->getTest('my-test')->getVariant('my-variant')->getCallback());
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantFromServiceManager
     * @expectedException RuntimeException
     * @expectedExceptionMessage The variant "my_service_manager_variant" is not a valid service manager name.
     */
    public function testServiceManagerVariantWithInvalidService()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'my_service_manager_variant',
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $factory = new EngineFactory();

        // Act
        $factory->createService($this->createMockedServiceLocator($config, true));
    }

    /**
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariants
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariant
     * @covers PhpAbModule\Service\EngineFactory::loadTestVariantFromServiceManager
     */
    public function testServiceManagerVariant()
    {
        // Arrange
        $config = [
            'phpab' => [
                'tests' => [
                    'my-test' => [
                        'filter' => 'my_filter',
                        'variant_chooser' => 'my_variant_chooser',
                        'variants' => [
                            'my-variant' => [
                                'type' => 'my_service_manager_variant',
                            ],
                        ],

                    ],
                ],
            ],
        ];

        $serviceLocator = $this->createMockedServiceLocator($config, true);
        $serviceLocator
            ->expects($this->at(5))
            ->method('has')
            ->with($this->equalTo('my_service_manager_variant'))
            ->willReturn(true);

        $serviceLocator
            ->expects($this->at(6))
            ->method('get')
            ->with($this->equalTo('my_service_manager_variant'))
            ->willReturn(new SimpleVariant('my-variant'));

        $factory = new EngineFactory();

        // Act
        $factory->createService($serviceLocator);
    }
}
