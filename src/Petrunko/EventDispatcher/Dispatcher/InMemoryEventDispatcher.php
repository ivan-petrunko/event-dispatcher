<?php

declare(strict_types=1);

namespace Petrunko\EventDispatcher\Dispatcher;

use Petrunko\EventDispatcher\Event\EventInterface;
use Petrunko\EventDispatcher\Exception\EventDispatcherException;
use Petrunko\EventDispatcher\Exception\UnsupportedEventException;
use Petrunko\EventDispatcher\Listener\EventListenerInterface;

class InMemoryEventDispatcher implements EventDispatcherInterface
{
    private static array $events = [];

    public function reset(): void
    {
        self::$events = [];
    }

    /**
     * {@inheritDoc}
     */
    public function addEventListener($event, EventListenerInterface ...$eventListeners): self
    {
        $eventFQCN = $this->getEventFQCNByEvent($event);
        foreach ($eventListeners as $eventListener) {
            if (!$eventListener->isSupport($event)) {
                throw new UnsupportedEventException(
                    sprintf('Event %s not supported by %s.', $eventFQCN, get_class($eventListener))
                );
            }
        }
        $eventHashByFQCN = $this->getEventHashByFQCN($eventFQCN);
        $eventHash = $eventHashByFQCN;
        foreach ($eventListeners as $eventListener) {
            self::$events[$eventHash][] = $eventListener;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(EventInterface $event): void
    {
        $eventHash = $this->getEventHash($event);
        if (empty(self::$events[$eventHash])) {
            return;
        }
        foreach (self::$events[$eventHash] as $eventListener) {
            /** @var EventListenerInterface $eventListener */
            $eventListener->handle($event);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function removeEvent($event): self
    {
        $eventHash = $this->getEventHash($event);
        unset(self::$events[$eventHash]);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventListeners($event): array
    {
        $eventHash = $this->getEventHash($event);
        return self::$events[$eventHash] ?? [];
    }

    /**
     * @param string|EventInterface $event
     * @return string
     * @throws EventDispatcherException
     */
    private function getEventHash($event): string
    {
        return $this->getEventHashByFQCN($this->getEventFQCNByEvent($event));
    }

    private function getEventHashByFQCN(string $eventFQCN): string
    {
        return md5($eventFQCN);
    }

    /**
     * @param string|EventInterface $event
     * @return string
     * @throws EventDispatcherException
     */
    private function getEventFQCNByEvent($event): string
    {
        try {
            $reflection = new \ReflectionClass($event);
        } catch (\ReflectionException $e) {
            throw new EventDispatcherException('Cannot get event class.', 500, $e);
        }
        if (!$reflection->implementsInterface(EventInterface::class)) {
            throw new EventDispatcherException(
                "Unsupported interface for event {$reflection->getName()}. Must implement EventInterface."
            );
        }
        return $reflection->getName();
    }
}
