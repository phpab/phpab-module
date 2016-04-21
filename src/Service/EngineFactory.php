<?php

namespace PhpAbModule\Service;

use PhpAb\Engine\Engine;
use PhpAb\Event\DispatcherInterface;
use PhpAb\Participation\ParticipationManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EngineFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ParticipationManagerInterface $participationManager */
        $participationManager = $serviceLocator->get('phpab.participation_manager');

        /** @var DispatcherInterface $dispatcher */
        $dispatcher = $serviceLocator->get('phpab.dispatcher');

        $filter = null;
        $chooser = null;

        if ($serviceLocator->has('phpab.filter')) {
            $filter = $serviceLocator->get('phpab.filter');
        }

        if ($serviceLocator->has('phpab.variant_chooser')) {
            $chooser = $serviceLocator->get('phpab.variant_chooser');
        }

        return new Engine($participationManager, $dispatcher, $filter, $chooser);
    }
}
