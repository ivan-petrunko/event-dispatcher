<?php

declare(strict_types=1);

namespace Petrunko\EventDispatcher\Listener;

use Petrunko\EventDispatcher\Event\EventInterface;

interface EventListenerInterface
{
    public function handle(EventInterface $event): void;

    /**
     * @param string|EventInterface $event
     * @return bool
     */
    public function isSupport($event): bool;
}
