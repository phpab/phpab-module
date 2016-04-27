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
use Zend\View\Helper\AbstractHelper;

/**
 * A view helper that can be used to render a script for analytics.
 */
class Script extends AbstractHelper
{
    private $analytics;

    /**
     * Initializes a new instance of this class.
     *
     * @param AbstractGoogleAnalytics $analytics The analytics to render a script for.
     */
    public function __construct(AbstractGoogleAnalytics $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * Generates the script that is needed to make phpab work.
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->analytics->getScript();
    }
}
