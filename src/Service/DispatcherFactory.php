<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModule\Service;

use PhpAb\Engine\Engine;
use PhpAb\Event\Dispatcher;
use PhpAb\Event\SubscriberInterface;
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
        $dispatcher = new Dispatcher();

        $config = $serviceLocator->get('Config');
        $collectorName = $config['phpab']['analytics']['collector'];

        if ($collectorName && $serviceLocator->has($collectorName)) {
            $dataCollector = $serviceLocator->get($collectorName);

            if (!$dataCollector instanceof SubscriberInterface) {
                throw new RuntimeException(sprintf(
                    'The data collector is not an instance of %s',
                    SubscriberInterface::class
                ));
            }

            $dispatcher->addSubscriber($dataCollector);
        }

        return $dispatcher;
    }
}
