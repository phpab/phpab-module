<?php

namespace PhpAbModuleTest\Controller\Plugin;

use PhpAb\Engine\EngineInterface;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAbModule\Controller\Plugin\IsActive;
use PHPUnit_Framework_TestCase;

class IsActiveTest extends PHPUnit_Framework_TestCase
{
    private $participationManager;

    protected function setUp()
    {
        $this->participationManager = $this->getMockForAbstractClass(ParticipationManagerInterface::class);
    }

    public function testInvokeWithValidTestAndVariant()
    {
        // Arrange
        $plugin = new IsActive($this->participationManager);

        // Act
        $result = $plugin('test', 'variant');

        // Assert
        $this->assertFalse($result);
    }

    public function testInvokeWithInvalidTest()
    {
        // Arrange
        $plugin = new IsActive($this->participationManager);

        // Act
        $result = $plugin('non-existing-test', 'variant');

        // Assert
        $this->assertFalse($result);
    }

    public function testInvokeWithInvalidVariant()
    {
        // Arrange
        $plugin = new IsActive($this->participationManager);

        // Act
        $result = $plugin('test', 'non-existing-variant');

        // Assert
        $this->assertFalse($result);
    }
}
