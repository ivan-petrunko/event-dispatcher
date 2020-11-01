<?php

declare(strict_types=1);

namespace Petrunko\EventDispatcher\Dispatcher;

use Petrunko\EventDispatcher\Event\EventInterface;
use Petrunko\EventDispatcher\Exception\EventDispatcherException;
use Petrunko\EventDispatcher\Listener\EventListenerInterface;

interface EventDispatcherInterface
{
    public function reset(): void;

    /**
     * @param string|EventInterface $event
     * @param EventListenerInterface ...$eventListeners
     * @return $this
     * @throws EventDispatcherException
     */
    public function addEventListener($event, EventListenerInterface ...$eventListeners): self;

    /**
     * @param EventInterface $event
     * @throws EventDispatcherException
     */
    public function dispatch(EventInterface $event): void;

    /**
     * @param string|EventInterface $event
     * @return $this
     * @throws EventDispatcherException
     */
    public function removeEvent($event): self;

    /**
     * @param string|EventInterface $event
     * @return array
     * @throws EventDispatcherException
     */
    public function getEventListeners($event): array;
}
