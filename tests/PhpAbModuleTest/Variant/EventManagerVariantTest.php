<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\Variant;

use PhpAb\Variant\VariantInterface;
use PhpAbModule\Variant\EventManagerVariant;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventManagerVariantTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers PhpAbModule\Variant\EventManagerVariant::__construct
     * @covers PhpAbModule\Variant\EventManagerVariant::getIdentifier
     */
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

    /**
     * @covers PhpAbModule\Variant\EventManagerVariant::__construct
     * @covers PhpAbModule\Variant\EventManagerVariant::getEventManager
     */
    public function testGetEventManager()
    {
        // Arrange
        $eventManager = new EventManager();
        $eventManagerVariant = new EventManagerVariant($eventManager, 'identifier', 'eventName', function() {}, 0);

        // Act
        $result = $eventManagerVariant->getEventManager();

        // Assert
        $this->assertEquals($eventManager, $result);
    }

    /**
     * @covers PhpAbModule\Variant\EventManagerVariant::__construct
     * @covers PhpAbModule\Variant\EventManagerVariant::getEventName
     */
    public function testGetEventName()
    {
        // Arrange
        $eventManager = new EventManager();
        $eventManagerVariant = new EventManagerVariant($eventManager, 'identifier', 'eventName', function() {}, 0);

        // Act
        $result = $eventManagerVariant->getEventName();

        // Assert
        $this->assertEquals('eventName', $result);
    }

    /**
     * @covers PhpAbModule\Variant\EventManagerVariant::__construct
     * @covers PhpAbModule\Variant\EventManagerVariant::getCallback
     */
    public function testGetCallback()
    {
        // Arrange
        $callback = function() {};

        $eventManager = new EventManager();
        $eventManagerVariant = new EventManagerVariant($eventManager, 'identifier', 'eventName', $callback, 0);

        // Act
        $result = $eventManagerVariant->getCallback();

        // Assert
        $this->assertEquals($callback, $result);
    }

    /**
     * @covers PhpAbModule\Variant\EventManagerVariant::__construct
     * @covers PhpAbModule\Variant\EventManagerVariant::getPriority
     */
    public function testGetPriority()
    {
        // Arrange
        $eventManager = new EventManager();
        $eventManagerVariant = new EventManagerVariant($eventManager, 'identifier', 'eventName', function() {}, 0);

        // Act
        $result = $eventManagerVariant->getPriority();

        // Assert
        $this->assertEquals(0, $result);
    }

    /**
     * @covers PhpAbModule\Variant\EventManagerVariant::run
     */
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
