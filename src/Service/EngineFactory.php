<?php

namespace PhpAbModule\Service;

use PhpAb\Engine\Engine;
use PhpAb\Engine\EngineInterface;
use PhpAb\Event\DispatcherInterface;
use PhpAb\Participation\ParticipationManagerInterface;
use PhpAb\Test\Test;
use PhpAb\Variant\CallbackVariant;
use PhpAb\Variant\SimpleVariant;
use RuntimeException;
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
        foreach ($config['tests'] as $key => $testConfig) {
            $this->loadTest($serviceLocator, $engine, $testConfig);
        }
    }

    private function loadTest(ServiceLocatorInterface $serviceLocator, EngineInterface $engine, array $config)
    {
        if (!array_key_exists('identifier', $config)) {
            throw new RuntimeException('Missing the "identifier" for the test.');
        }

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

        $test = new Test($config['identifier'], $variants);

        $engine->addTest($test, $options, $filter, $variantChooser);
    }

    private function loadTestVariants(ServiceLocatorInterface $serviceLocator, $config)
    {
        $variants = [];

        foreach ($config['variants'] as $variant) {
            if (!array_key_exists('type', $variant)) {
                throw new RuntimeException('The type of the variant is missing.');
            }

            $options = empty($variant['options']) ? [] : $variant['options'];

            $variants[] = $this->loadTestVariant($serviceLocator, $variant['type'], $options);
        }

        return $variants;
    }

    private function loadTestVariant(ServiceLocatorInterface $serviceLocator, $type, array $options)
    {
        switch ($type) {
            case 'callback':
                $variant = $this->loadTestVariantCallback($serviceLocator, $options);
                break;

            case 'simple':
                $variant = $this->loadTestVariantSimple($serviceLocator, $options);
                break;

            default:
                $variant = $this->loadTestVariantFromServiceManager($serviceLocator, $type);
                break;
        }

        return $variant;
    }

    private function loadTestVariantCallback(ServiceLocatorInterface $serviceLocator, array $options)
    {
        if (!array_key_exists('identifier', $options)) {
            throw new RuntimeException('Missing "identifier" for callback variant.');
        }

        if (!array_key_exists('callback', $options)) {
            throw new RuntimeException('Missing "callback" for callback variant.');
        }

        if (!is_callable($options['callback'])) {
            throw new RuntimeException('The "callback" for callback variant cannot be called.');
        }

        return new CallbackVariant($options['identifier'], $options['callback']);
    }

    private function loadTestVariantSimple(ServiceLocatorInterface $serviceLocator, array $options)
    {
        if (!array_key_exists('identifier', $options)) {
            throw new RuntimeException('Missing "identifier" for simple variant.');
        }

        return new SimpleVariant($options['identifier']);
    }

    private function loadTestVariantFromServiceManager(ServiceLocatorInterface $serviceLocator, $type)
    {
        if (!$serviceLocator->has($type)) {
            throw new RuntimeException(sprintf('The variant "%s" could not be loaded.', $type));
        }

        return $serviceLocator->get($type);
    }
}
