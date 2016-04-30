<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModule\Controller\Plugin;

use PhpAb\Participation\ManagerInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * A controller plugin that can be used to check if a test is active.
 */
class IsActive extends AbstractPlugin
{
    /**
     * @var ManagerInterface The participation manager used to check participation.
     */
    private $participationManager;

    /**
     * Initializes a new instance of this class.
     *
     * @param ManagerInterface $participationManager The participation manager used to check participation.
     */
    public function __construct(ManagerInterface $participationManager)
    {
        $this->participationManager = $participationManager;
    }

    /**
     * Checks if the test with the given variant is active.
     *
     * @param string $test The identifier of the test to check for activity.
     * @param string $variant The identifier of the variant to check for activity.
     * @return bool
     */
    public function __invoke($test, $variant)
    {
        return $this->participationManager->participates($test, $variant) !== false;
    }
}
