<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\Service;

use PhpAb\Participation\Manager;
use PhpAb\Participation\PercentageFilter;
use PhpAb\Storage\Runtime;
use PhpAbModule\Service\DefaultFilterFactory;
use PhpAbModule\Service\ParticipationManagerFactory;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultFilterFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        // Arrange
        $serviceLocator = $this->getMockForAbstractClass(ServiceLocatorInterface::class);
        $service = new DefaultFilterFactory();

        // Act
        $result = $service->createService($serviceLocator);

        // Assert
        $this->assertInstanceOf(PercentageFilter::class, $result);
    }
}
