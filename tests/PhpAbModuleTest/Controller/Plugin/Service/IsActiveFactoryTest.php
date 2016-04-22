<?php

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
