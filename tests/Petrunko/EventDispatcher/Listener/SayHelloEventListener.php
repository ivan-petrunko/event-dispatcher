<?php

declare(strict_types=1);

namespace Tests\Petrunko\EventDispatcher\Listener;

use Petrunko\EventDispatcher\Event\EventInterface;
use Petrunko\EventDispatcher\Listener\EventListenerInterface;
use Tests\Petrunko\EventDispatcher\Event\SayHelloEvent;

class SayHelloEventListener implements EventListenerInterface
{
    public function handle(EventInterface $event): void
    {
        echo "Hello, {$event->getName()}!";
    }

    /**
     * {@inheritDoc}
     */
    public function isSupport($event): bool
    {
        return $event instanceof SayHelloEvent
            || $event === SayHelloEvent::class;
    }
}
