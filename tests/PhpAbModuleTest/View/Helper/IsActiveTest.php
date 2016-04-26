<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\View\Helper\Plugin;

use PhpAb\Engine\EngineInterface;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAbModule\View\Helper\IsActive;
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
