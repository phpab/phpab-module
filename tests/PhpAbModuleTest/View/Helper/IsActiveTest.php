<?php

namespace PhpAbModuleTest\View\Helper\Plugin;

use PhpAb\Engine\EngineInterface;
use PhpAbModule\View\Helper\IsActive;
use PHPUnit_Framework_TestCase;

class IsActiveTest extends PHPUnit_Framework_TestCase
{
    public function testInvokeWithValidTestAndVariant()
    {
        // Arrange
        $engine = $this->getMockForAbstractClass(EngineInterface::class);
        $plugin = new IsActive($engine);

        // Act
        $result = $plugin('test', 'variant');

        // Assert
        $this->assertFalse($result);
    }

    public function testInvokeWithInvalidTest()
    {
        // Arrange
        $engine = $this->getMockForAbstractClass(EngineInterface::class);
        $plugin = new IsActive($engine);

        // Act
        $result = $plugin('non-existing-test', 'variant');

        // Assert
        $this->assertFalse($result);
    }

    public function testInvokeWithInvalidVariant()
    {
        // Arrange
        $engine = $this->getMockForAbstractClass(EngineInterface::class);
        $plugin = new IsActive($engine);

        // Act
        $result = $plugin('test', 'non-existing-variant');

        // Assert
        $this->assertFalse($result);
    }
}
