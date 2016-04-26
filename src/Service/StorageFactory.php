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
use PhpAb\Storage\Cookie;
use PhpAb\Storage\Runtime;
use PhpAb\Storage\Session;
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
                $storage = new Cookie($options['name'], $options['ttl']);
                break;

            case 'runtime':
                $storage = new Runtime();
                break;

            case 'session':
                $storage = new Session($options['name']);
                break;

            default:
                throw new RuntimeException('Invalid storage provider set: ' . $config['phpab']['storage']);
        }

        return $storage;
    }
}
