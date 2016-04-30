<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModule\View\Helper\Service;

use PhpAbModule\View\Helper\Script;
use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ScriptFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceManager = $serviceLocator->getServiceLocator();

        $config = $serviceManager->get('Config');

        if (!$serviceManager->has($config['phpab']['analytics']['handler'])) {
            throw new RuntimeException(sprintf(
                'The data renderer "%s" does not exists.',
                $config['phpab']['analytics']['handler']
            ));
        }

        $renderer = $serviceManager->get($config['phpab']['analytics']['handler']);

        return new Script($renderer);
    }
}
