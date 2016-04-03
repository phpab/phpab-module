<?php

namespace PhpAbModule\View\Helper;

use PhpAb\Engine\EngineInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * A view helper that can be used to check if a test is active.
 */
class IsActive extends AbstractHelper
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
        // return $this->runner->....???

        return false;
    }
}
