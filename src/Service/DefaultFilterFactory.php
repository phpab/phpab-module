<?php

namespace PhpAbModule\Service;

use PhpAb\Participation\PercentageFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultFilterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PercentageFilter(100);
    }
}
