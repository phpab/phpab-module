<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\Service;

use PhpAb\Analytics\Google\DataCollector;
use PhpAb\Event\Dispatcher;
use PhpAbModule\Service\DispatcherFactory;
use PHPUnit_Framework_TestCase;
use stdClass;
use Zend\ServiceManager\ServiceLocatorInterface;

class DispatcherFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testWithoutDataCollector()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $service = new DispatcherFactory();

        // Act
        $result = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(Dispatcher::class, $result);
    }

    public function testWithDataCollector()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('Config'))
            ->willReturn([
                'phpab' => [
                    'analytics' => [
                        'collector' => 'my-collector',
                    ],
                ],
            ]);

        $serviceLocator
            ->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo('my-collector'))
            ->willReturn(true);


        $serviceLocator
            ->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo('my-collector'))
            ->willReturn(new DataCollector());

        $service = new DispatcherFactory();

        // Act
        $result = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(Dispatcher::class, $result);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage The data collector is not an instance of PhpAb\Event\SubscriberInterface
     */
    public function testWithInvalidDataCollector()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('Config'))
            ->willReturn([
                'phpab' => [
                    'analytics' => [
                        'collector' => 'my-collector',
                    ],
                ],
            ]);

        $serviceLocator
            ->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo('my-collector'))
            ->willReturn(true);


        $serviceLocator
            ->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo('my-collector'))
            ->willReturn(new stdClass());

        $service = new DispatcherFactory();

        // Act
        $result = $service->createService($serviceLocator);
    }
}
