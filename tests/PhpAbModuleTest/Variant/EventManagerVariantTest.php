<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModule\Variant;

use PhpAb\Variant\VariantInterface;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventManagerVariantTest extends PHPUnit_Framework_TestCase
{
    public function testGetIdentifier()
    {
        // Arrange
        $eventManager = new EventManager();
        $eventManagerVariant = new EventManagerVariant($eventManager, 'identifier', 'eventName', function() {}, 0);

        // Act
        $result = $eventManagerVariant->getIdentifier();

        // Assert
        $this->assertEquals('identifier', $result);
    }

    public function testRun()
    {
        // Arrange
        $eventManager = $this->getMock(EventManager::class);
        $eventManagerVariant = new EventManagerVariant($eventManager, 'identifier', 'eventName', function() {}, 0);

        // Assert
        $eventManager->expects($this->once())->method('attach');

        // Act
        $eventManagerVariant->run();
    }
}
