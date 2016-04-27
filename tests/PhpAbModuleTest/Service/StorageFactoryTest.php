<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModule\Service;

use PhpAb\Storage\Cookie;
use PhpAb\Storage\Runtime;
use PhpAb\Storage\Session;
use phpmock\functions\FixedValueFunction;
use phpmock\Mock;
use phpmock\MockBuilder;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceLocatorInterface;

class StorageFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateServiceWithInvalidStorage()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with($this->equalTo('Config'))->willReturn([
            'phpab' => [
                'storage' => 'unknown',
                'storage_options' => [
                ],
            ],
        ]);

        // Assert
        $this->setExpectedException('RuntimeException', 'Invalid storage provider set: unknown');

        $service = new StorageFactory();

        // Act
        $storage = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(Runtime::class, $storage);
    }

    public function testCreateServiceRuntime()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with($this->equalTo('Config'))->willReturn([
            'phpab' => [
                'storage' => 'runtime',
                'storage_options' => [
                ],
            ],
        ]);

        $service = new StorageFactory();

        // Act
        $storage = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(Runtime::class, $storage);
    }

    public function testCreateServiceRuntimeWithoutStorageOptions()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with($this->equalTo('Config'))->willReturn([
            'phpab' => [
                'storage' => 'runtime',
            ],
        ]);

        $service = new StorageFactory();

        // Act
        $storage = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(Runtime::class, $storage);
    }

    public function testCreateServiceCookie()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with($this->equalTo('Config'))->willReturn([
            'phpab' => [
                'storage' => 'cookie',
                'storage_options' => [
                    'name' => 'namespace',
                    'ttl' => 3600,
                ],
            ],
        ]);

        $service = new StorageFactory();

        // Act
        $storage = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(Cookie::class, $storage);
    }

    public function testCreateServiceSession()
    {
        // Arrange
        $builder = new MockBuilder();
        $builder->setNamespace('PhpAb\\Storage');
        $builder->setName("session_status");
        $builder->setFunctionProvider(new FixedValueFunction(PHP_SESSION_ACTIVE));

        $sessionStatusMock = $builder->build();
        $sessionStatusMock->enable();

        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with($this->equalTo('Config'))->willReturn([
            'phpab' => [
                'storage' => 'session',
                'storage_options' => [
                    'name' => 'namespace',
                ],
            ],
        ]);

        $service = new StorageFactory();

        // Act
        $storage = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(Session::class, $storage);
    }
}
