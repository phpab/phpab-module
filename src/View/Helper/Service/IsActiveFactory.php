<?php

namespace PhpAbModule\View\Helper\Service;

use PhpAbModule\View\Helper\IsActive;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IsActiveFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $participationManager = $serviceLocator->getServiceLocator()->get('phpab.participation_manager');

        return new IsActive($participationManager);
    }
}
