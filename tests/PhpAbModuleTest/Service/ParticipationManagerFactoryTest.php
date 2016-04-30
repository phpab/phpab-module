<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\Service;

use PhpAb\Participation\ManagerInterface;
use PhpAb\Storage\Runtime;
use PhpAbModule\Service\ParticipationManagerFactory;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceLocatorInterface;

class ParticipationManagerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('has')
            ->with($this->equalTo('phpab.storage'))
            ->willReturn(true);
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('phpab.storage'))
            ->willReturn(new Runtime());

        $service = new ParticipationManagerFactory();

        // Act
        $result = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(ManagerInterface::class, $result);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Missing service "phpab.storage" so cannot create participation manager.
     */
    public function testCreateServiceWithMissingStorage()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('has')
            ->with($this->equalTo('phpab.storage'))
            ->willReturn(false);

        $service = new ParticipationManagerFactory();

        // Act
        $service->createService($serviceLocator);
    }
}
