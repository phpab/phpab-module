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
use PhpAb\Engine\EngineInterface;
use PhpAb\Event\DispatcherInterface;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAb\Test\Test;
use PhpAb\Variant\CallbackVariant;
use PhpAb\Variant\SimpleVariant;
use PhpAbModule\Variant\EventManagerVariant;
use RuntimeException;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EngineFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var array $config */
        $config = $serviceLocator->get('Config');

        /** @var ParticipationManagerInterface $participationManager */
        $participationManager = $serviceLocator->get('phpab.participation_manager');

        /** @var DispatcherInterface $dispatcher */
        $dispatcher = $serviceLocator->get('phpab.dispatcher');

        $filter = $this->loadService($serviceLocator, $config['phpab']['default_filter']);
        $chooser = $this->loadService($serviceLocator, $config['phpab']['default_variant_chooser']);

        $engine = new Engine($participationManager, $dispatcher, $filter, $chooser);

        $this->loadTests($engine, $serviceLocator, $config['phpab']);

        return $engine;
    }

    private function loadService(ServiceLocatorInterface $serviceLocator, $serviceName)
    {
        if (!$serviceLocator->has($serviceName)) {
            return null;
        }

        return $serviceLocator->get($serviceName);
    }

    private function loadTests(EngineInterface $engine, ServiceLocatorInterface $serviceLocator, $config)
    {
        foreach ($config['tests'] as $identifier => $testConfig) {
            $this->loadTest($serviceLocator, $engine, $identifier, $testConfig);
        }
    }

    private function loadTest(ServiceLocatorInterface $serviceLocator, EngineInterface $engine, $identifier, array $config)
    {
        $filter = null;
        if (array_key_exists('filter', $config)) {
            $filter = $serviceLocator->get($config['filter']);
        }

        $variantChooser = null;
        if (array_key_exists('variant_chooser', $config)) {
            $variantChooser = $serviceLocator->get($config['variant_chooser']);
        }

        $variants = $this->loadTestVariants($serviceLocator, $config);
        $options = array_key_exists('options', $config) ? $config['options'] : [];

        $test = new Test($identifier, $variants);

        $engine->addTest($test, $options, $filter, $variantChooser);
    }

    private function loadTestVariants(ServiceLocatorInterface $serviceLocator, $config)
    {
        $variants = [];

        foreach ($config['variants'] as $identifier => $variant) {

            // We also support shortcuts so that the user can specify a service name straight away.
            if (is_string($variant)) {
                $variant = [
                    'type' => $variant,
                ];
            }

            if (!array_key_exists('type', $variant)) {
                throw new RuntimeException('The type of the variant is missing.');
            }

            $options = empty($variant['options']) ? [] : $variant['options'];

            $variants[] = $this->loadTestVariant($serviceLocator, $variant['type'], $identifier, $options);
        }

        return $variants;
    }

    private function loadTestVariant(ServiceLocatorInterface $serviceLocator, $type, $identifier, array $options)
    {
        switch ($type) {
            case 'callback':
                $variant = $this->loadTestVariantCallback($serviceLocator, $identifier, $options);
                break;

            case 'event_manager':
                $variant = $this->loadTestVariantEventManager($serviceLocator, $identifier, $options);
                break;

            case 'simple':
                $variant = $this->loadTestVariantSimple($serviceLocator, $identifier, $options);
                break;

            case 'service_manager':
            default:
                $variant = $this->loadTestVariantFromServiceManager($serviceLocator, $type);
                break;
        }

        return $variant;
    }

    private function loadTestVariantCallback(ServiceLocatorInterface $serviceLocator, $identifier, array $options)
    {
        if (!array_key_exists('callback', $options)) {
            throw new RuntimeException('Missing "callback" for callback variant.');
        }

        if (!is_callable($options['callback'])) {
            throw new RuntimeException('The "callback" for callback variant cannot be called.');
        }

        return new CallbackVariant($identifier, $options['callback']);
    }

    private function loadTestVariantEventManager(ServiceLocatorInterface $serviceLocator, $identifier, array $options)
    {
        if (empty($options['event_manager'])) {
            $eventManager = $serviceLocator->get('Application')->getEventManager();
        } else {
            $eventManager = $serviceLocator->get($options['event_manager']);
        }

        $callback = $options['callback'];

        if (!is_callable($callback) && $serviceLocator->has($callback)) {
            $callback = $serviceLocator->get($callback);
        }

        return new EventManagerVariant(
            $eventManager,
            $identifier,
            $options['event'],
            $callback,
            $options['priority']
        );
    }

    private function loadTestVariantSimple(ServiceLocatorInterface $serviceLocator, $identifier, array $options)
    {
        return new SimpleVariant($identifier);
    }

    private function loadTestVariantFromServiceManager(ServiceLocatorInterface $serviceLocator, $type)
    {
        if (!$serviceLocator->has($type)) {
            throw new RuntimeException(sprintf('The variant "%s" is not a valid service manager name.', $type));
        }

        return $serviceLocator->get($type);
    }
}
