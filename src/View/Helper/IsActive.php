<?php

namespace PhpAbModule\View\Helper;

use PhpAb\Participation\ParticipationManagerInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * A view helper that can be used to check if a test is active.
 */
class IsActive extends AbstractHelper
{
    /**
     * @var ParticipationManagerInterface The participation manager used to check participation.
     */
    private $participationManager;

    /**
     * Initializes a new instance of this class.
     *
     * @param ParticipationManagerInterface $participationManager The participation manager used to check participation.
     */
    public function __construct(ParticipationManagerInterface $participationManager)
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
        return $this->participationManager->participates($test, $variant) === true;
    }
}
