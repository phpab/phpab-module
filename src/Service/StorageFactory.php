<?php
/**
 * This file is part of phpab/phpab-module. (https://github.com/phpab/phpab-module)
 *
 * @link https://github.com/phpab/phpab-module for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://raw.githubusercontent.com/phpab/phpab-module/master/LICENSE MIT
 */

namespace PhpAbModule\Service;

use PhpAb\Storage\Adapter\Cookie;
use PhpAb\Storage\Adapter\Runtime;
use PhpAb\Storage\Adapter\Session;
use PhpAb\Storage\Adapter\ZendSession;
use PhpAb\Storage\Storage;
use RuntimeException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StorageFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (array_key_exists('storage_options', $config['phpab'])) {
            $options = $config['phpab']['storage_options'];
        } else {
            $options = [];
        }

        switch ($config['phpab']['storage']) {
            case 'cookie':
                $adapter = new Cookie($options['name'], $options['ttl']);
                break;

            case 'runtime':
                $adapter = new Runtime();
                break;

            case 'session':
                $adapter = new ZendSession($options['name']);
                break;

            default:
                throw new RuntimeException('Invalid storage adapter set: ' . $config['phpab']['storage']);
        }

        return new Storage($adapter);
    }

    private function loadContainer(ServiceLocatorInterface $serviceLocator)
    {
    }
}
