<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

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
        //$result = $factory->createService($serviceLocator);

        // Assert
        //$this->assertNull($result);
    }
}
