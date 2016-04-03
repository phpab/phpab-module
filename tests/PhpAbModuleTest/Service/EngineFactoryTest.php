<?php

namespace PhpAbModuleTest\Service;

use PhpAbModule\Service\EngineFactory;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class EngineFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        // Arrange
        $factory = new EngineFactory();
        $serviceLocator = $this->getMock(ServiceManager::class);

        // Act
        $result = $factory->createService($serviceLocator);

        // Assert
        $this->assertNull($result);
    }
}
