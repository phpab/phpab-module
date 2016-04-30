<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModuleTest\View\Helper\Plugin;

use PhpAb\Analytics\Renderer\JavascriptRendererInterface;
use PhpAbModule\View\Helper\Script;
use PHPUnit_Framework_TestCase;

class ScriptTest extends PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        // Arrange
        $renderer = $this->getMockForAbstractClass(JavascriptRendererInterface::class);
        $helper = new Script($renderer);

        // Assert
        $renderer->expects($this->once())->method('getScript');

        // Act
        $helper->__invoke();
    }
}
