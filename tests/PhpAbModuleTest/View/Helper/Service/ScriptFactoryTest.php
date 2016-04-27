<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\View\Helper\Service;

use PhpAb\Analytics\Renderer\AbstractGoogleAnalytics;
use PhpAbModule\View\Helper\Script;
use PhpAbModule\View\Helper\Service\ScriptFactory;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ScriptFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers PhpAbModule\View\Helper\Service\ScriptFactory::createService
     */
    public function testWithValidRenderer()
    {
        // Arrange
        $factory = new ScriptFactory();

        $renderer = $this->getMockForAbstractClass(AbstractGoogleAnalytics::class);

        $serviceManager = $this->getMockForAbstractClass(ServiceLocatorInterface::class);

        $serviceManager
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('Config'))
            ->willReturn([
                'phpab' => [
                    'analytics' => [
                        'renderer' => 'my-renderer',
                    ],
                ],
            ]);

        $serviceManager
            ->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo('my-renderer'))
            ->willReturn(true);

        $serviceManager
            ->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo('my-renderer'))
            ->willReturn($renderer);

        $serviceLocator = $this
            ->getMockBuilder(AbstractPluginManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getServiceLocator'])
            ->getMockForAbstractClass();

        $serviceLocator->expects($this->once())->method('getServiceLocator')->willReturn($serviceManager);

        // Act
        $result = $factory->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(Script::class, $result);
    }

    /**
     * @covers PhpAbModule\View\Helper\Service\ScriptFactory::createService
     * @expectedException RuntimeException
     * @expectedExceptionMessage The data renderer "my-renderer" does not exists.
     */
    public function testWithInvalidRenderer()
    {
        // Arrange
        $factory = new ScriptFactory();

        $serviceManager = $this->getMockForAbstractClass(ServiceLocatorInterface::class);

        $serviceManager
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('Config'))
            ->willReturn([
                'phpab' => [
                    'analytics' => [
                        'renderer' => 'my-renderer',
                    ],
                ],
            ]);

        $serviceManager
            ->expects($this->at(1))
            ->method('has')
            ->with($this->equalTo('my-renderer'))
            ->willReturn(false);

        $serviceLocator = $this
            ->getMockBuilder(AbstractPluginManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['getServiceLocator'])
            ->getMockForAbstractClass();

        $serviceLocator->expects($this->once())->method('getServiceLocator')->willReturn($serviceManager);

        // Act
        $result = $factory->createService($serviceLocator);
    }
}
