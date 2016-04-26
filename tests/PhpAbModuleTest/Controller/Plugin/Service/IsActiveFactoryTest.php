<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\Controller\Plugin\Service;

use PhpAb\Participation\ParticipationManagerInterface;
use PhpAbModule\Controller\Plugin\IsActive;
use PhpAbModule\Controller\Plugin\Service\IsActiveFactory;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class IsActiveFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        // Arrange
        $factory = new IsActiveFactory();

        $participationManager = $this->getMockForAbstractClass(ParticipationManagerInterface::class);

        $serviceManager = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceManager
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('phpab.participation_manager'))
            ->willReturn($participationManager);

        $serviceLocator = $this
            ->getMockBuilder(AbstractPluginManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getServiceLocator'])
            ->getMockForAbstractClass();
        $serviceLocator->expects($this->any())->method('getServiceLocator')->willReturn($serviceManager);

        // Act
        $result = $factory->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(IsActive::class, $result);
    }
}
