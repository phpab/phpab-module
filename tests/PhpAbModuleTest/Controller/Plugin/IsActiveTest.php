<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\Controller\Plugin;

use PhpAb\Participation\ManagerInterface;
use PhpAbModule\Controller\Plugin\IsActive;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class IsActiveTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $participationManager;

    protected function setUp()
    {
        $this->participationManager = $this->getMockForAbstractClass(ManagerInterface::class);
    }

    public function testWithParticipationManagerReturningTrue()
    {
        // Arrange
        $plugin = new IsActive($this->participationManager);
        $this->participationManager->expects($this->once())->method('participates')->willReturn(true);

        // Act
        $result = $plugin('test', 'variant');

        // Assert
        $this->assertTrue($result);
    }

    public function testWithParticipationManagerReturningFalse()
    {
        // Arrange
        $plugin = new IsActive($this->participationManager);
        $this->participationManager->expects($this->once())->method('participates')->willReturn(false);

        // Act
        $result = $plugin('test', 'variant');

        // Assert
        $this->assertFalse($result);
    }

    public function testWithParticipationManagerReturningVariant()
    {
        // Arrange
        $plugin = new IsActive($this->participationManager);
        $this->participationManager->expects($this->once())->method('participates')->willReturn('variant');

        // Act
        $result = $plugin('test', 'variant');

        // Assert
        $this->assertTrue($result);
    }
}
