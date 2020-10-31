<?php

declare(strict_types=1);

namespace Petrunko\EventDispatcher\Dispatcher;

use Petrunko\EventDispatcher\Event\EventInterface;
use Petrunko\EventDispatcher\Exception\EventDispatcherException;
use Petrunko\EventDispatcher\Listener\EventListenerInterface;

interface EventDispatcherInterface
{
    /**
     * @param string|EventInterface $event
     * @param EventListenerInterface $eventListener
     * @throws EventDispatcherException
     */
    public function addEventListener($event, EventListenerInterface $eventListener): void;

    /**
     * @param EventInterface $event
     * @throws EventDispatcherException
     */
    public function dispatch(EventInterface $event): void;

    /**
     * @param string|EventInterface $event
     * @throws EventDispatcherException
     */
    public function removeEvent($event): void;

    /**
     * @param string|EventInterface $event
     * @return array
     * @throws EventDispatcherException
     */
    public function getEventListeners($event): array;
}
