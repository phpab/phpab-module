<?php

namespace PhpAbModule\Service;

use PhpAb\Engine\Engine;
use PhpAb\Event\Dispatcher;
use PhpAb\Storage\Cookie;
use PhpAb\Storage\Runtime;
use PhpAb\Storage\Session;
use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DispatcherFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Dispatcher();
    }
}
