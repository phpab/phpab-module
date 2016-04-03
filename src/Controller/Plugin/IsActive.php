<?php

namespace PhpAbModule\Controller\Plugin;

use PhpAb\Engine\EngineInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * A controller plugin that can be used to check if a test is active.
 */
class IsActive extends AbstractPlugin
{
    /**
     * @var EngineInterface The engine used to execute tests.
     */
    private $engine;

    /**
     * Initializes a new instance of this class.
     *
     * @param EngineInterface $engine The engine used to execute tests.
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
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
        // TODO: Check if the test is active.

        return false;
    }
}
