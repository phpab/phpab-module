<?php

namespace PhpAbModule\Variant;

use PhpAb\Variant\VariantInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventManagerVariant implements VariantInterface
{
    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var string
     */
    private $idenfier;

    /**
     * @var string
     */
    private $eventName;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var int
     */
    private $priority;

    public function __construct(EventManagerInterface $eventManager, $identifier, $eventName, $callback, $priority)
    {
        $this->eventManager = $eventManager;
        $this->idenfier = $identifier;
        $this->eventName = $eventName;
        $this->callback = $callback;
        $this->priority = 0;
    }

    public function getIdentifier()
    {
        return $this->idenfier;
    }

    public function run()
    {
        $this->eventManager->attach($this->eventName, $this->callback, $this->priority);
    }
}
