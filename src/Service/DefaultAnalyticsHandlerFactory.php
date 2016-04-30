<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModule\Service;

use PhpAb\Analytics\Renderer\Google\GoogleUniversalAnalytics;
use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultAnalyticsHandlerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $collectorServiceName = $config['phpab']['analytics_collector'];

        if (!$serviceLocator->has($collectorServiceName)) {
            throw new RuntimeException(sprintf(
                'The data collector "%s" does not exists.',
                $config['phpab']['analytics_collector']
            ));
        }

        $analyticsData = $serviceLocator->get($collectorServiceName);

        return new GoogleUniversalAnalytics($analyticsData->getTestsData());
    }
}
