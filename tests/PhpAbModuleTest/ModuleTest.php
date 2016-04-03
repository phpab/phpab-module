<?php

namespace PhpAbModuleTest;

use PhpAb\Engine\EngineInterface;
use PhpAbModule\Module;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\ServiceManager;

class ModuleTest extends PHPUnit_Framework_TestCase
{
    public function testGetConfig()
    {
        // Arrange
        $module = new Module();

        // Act
        $result = $module->getConfig();

        // Assert
        $this->assertInternalType('array', $result);
    }

    public function testInit()
    {
        // Arrange
        $module = new Module();

        $eventManager = $this->getMock(EventManagerInterface::class);

        $moduleManager = $this->getMockBuilder(ModuleManager::class)->disableOriginalConstructor()->getMock();
        $moduleManager->method('getEventManager')->willReturn($eventManager);

        // Assert
        $eventManager->expects($this->once())->method('attach');

        // Act
        $module->init($moduleManager);
    }

    public function testOnLoadModulesPost()
    {
        // Arrange
        $module = new Module();
        $moduleEvent = $this->getMock(ModuleEvent::class);

        $engine = $this->getMockForAbstractClass(EngineInterface::class);
        $serviceManager = $this->getMock(ServiceManager::class);

        // Assert
        $moduleEvent->expects($this->once())->method('getParam')->willReturn($serviceManager);
        $serviceManager
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('PhpAbModule\\Engine'))
            ->willReturn($engine);
        $engine->expects($this->once())->method('start');

        // Act
        $module->onLoadModulesPost($moduleEvent);
    }
}
