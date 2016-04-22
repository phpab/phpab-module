<?php

namespace PhpAbModule\Service;

use PhpAb\Variant\RandomChooser;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultVariantChooserFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RandomChooser();
    }
}
