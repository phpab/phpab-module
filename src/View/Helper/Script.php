<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModule\View\Helper;

use PhpAb\Analytics\Renderer\AbstractGoogleAnalytics;
use PhpAb\Analytics\Renderer\JavascriptRendererInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * A view helper that can be used to render a script for analytics.
 */
class Script extends AbstractHelper
{
    /**
     * @var JavascriptRendererInterface
     */
    private $renderer;

    /**
     * Initializes a new instance of this class.
     *
     * @param JavascriptRendererInterface $renderer The analytics to render a script for.
     */
    public function __construct(JavascriptRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Generates the script that is needed to make phpab work.
     *
     * @return string
     */
    public function __invoke()
    {
        if ($this->renderer instanceof JavascriptRendererInterface) {
            return $this->renderer->getScript();
        }

        return '';
    }
}
