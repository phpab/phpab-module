<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\Service;

use PhpAb\Analytics\DataCollector\Google;
use PhpAb\Analytics\Renderer\Google\GoogleUniversalAnalytics;
use PhpAbModule\Service\DefaultAnalyticsHandlerFactory;
use PHPUnit_Framework_TestCase;
use RuntimeException;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultAnalyticsHandlerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->at(1))->method('has')->willReturn(true);
        $serviceLocator->expects($this->at(0))->method('get')->with($this->equalTo('Config'))->willReturn([
            'phpab' => [
                'analytics' => [
                    'collector' => 'my-collector',
                ],
            ],
        ]);
        $serviceLocator->expects($this->at(2))->method('get')->with($this->equalTo('my-collector'))->willReturn(
            new Google()
        );

        $service = new DefaultAnalyticsHandlerFactory();

        // Act
        $result = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(GoogleUniversalAnalytics::class, $result);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage The data collector "" does not exists.
     */
    public function testCreateServiceWithoutDataCollector()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('has')->willReturn(false);

        $service = new DefaultAnalyticsHandlerFactory();

        // Act
        $result = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(GoogleUniversalAnalytics::class, $result);
    }
}
