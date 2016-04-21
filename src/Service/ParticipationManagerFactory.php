<?php

namespace PhpAbModule\Service;

use PhpAb\Engine\Engine;
use PhpAb\Participation\Manager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ParticipationManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $storage = $serviceLocator->get('phpab.storage');

        return new Manager($storage);
    }
}
