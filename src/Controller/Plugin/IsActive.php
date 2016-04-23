<?php

namespace PhpAbModule\Controller\Plugin;

use PhpAb\Participation\ParticipationManagerInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * A controller plugin that can be used to check if a test is active.
 */
class IsActive extends AbstractPlugin
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
        return $this->participationManager->participates($test, $variant) !== false;
    }
}
