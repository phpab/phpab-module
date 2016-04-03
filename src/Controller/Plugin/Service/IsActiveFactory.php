<?php

namespace PhpAbModule\Controller\Plugin\Service;

use PhpAbModule\Controller\Plugin\IsActive;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IsActiveFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $engine = $serviceLocator->getServiceLocator()->get('PhpAbModule\\Engine');

        return new IsActive($engine);
    }
}
