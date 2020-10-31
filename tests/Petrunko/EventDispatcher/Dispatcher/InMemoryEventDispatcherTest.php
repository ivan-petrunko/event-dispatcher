<?php

declare(strict_types=1);

namespace Tests\Petrunko\EventDispatcher\Dispatcher;

use Petrunko\EventDispatcher\Dispatcher\EventDispatcherInterface;
use Petrunko\EventDispatcher\Dispatcher\InMemoryEventDispatcher;
use Petrunko\EventDispatcher\Event\EventInterface;
use Petrunko\EventDispatcher\Exception\UnsupportedEventException;
use Petrunko\EventDispatcher\Listener\EventListenerInterface;
use PHPUnit\Framework\TestCase;
use Tests\Petrunko\EventDispatcher\Event\SayGoodbyeEvent;
use Tests\Petrunko\EventDispatcher\Event\SayHelloEvent;
use Tests\Petrunko\EventDispatcher\Listener\SayGoodbyeEventListener;
use Tests\Petrunko\EventDispatcher\Listener\SayHelloEventListener;

class InMemoryEventDispatcherTest extends TestCase
{
    private EventDispatcherInterface $eventDispatcher;
    private EventInterface $helloEvent;
    private EventListenerInterface $helloEventListener;
    private EventInterface $goodbyeEvent;
    private EventListenerInterface $goodbyeEventListener;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventDispatcher = new InMemoryEventDispatcher();
        $this->helloEvent = new SayHelloEvent('Ivan');
        $this->helloEventListener = new SayHelloEventListener();
        $this->goodbyeEvent = new SayGoodbyeEvent('Ivan');
        $this->goodbyeEventListener = new SayGoodbyeEventListener();
    }

    public function testAll(): void
    {
        // test support
        self::assertTrue($this->helloEventListener->isSupport($this->helloEvent));
        self::assertFalse($this->helloEventListener->isSupport($this->goodbyeEvent));
        self::assertTrue($this->goodbyeEventListener->isSupport($this->goodbyeEvent));
        self::assertFalse($this->goodbyeEventListener->isSupport($this->helloEvent));

        // test no event listeners
        self::assertEquals([], $this->eventDispatcher->getEventListeners(SayHelloEvent::class));
        self::assertEquals([], $this->eventDispatcher->getEventListeners($this->helloEvent));
        self::assertEquals([], $this->eventDispatcher->getEventListeners(SayGoodbyeEvent::class));
        self::assertEquals([], $this->eventDispatcher->getEventListeners($this->goodbyeEvent));

        // test 1 event listener for event sayHello
        $this->eventDispatcher->addEventListener(SayHelloEvent::class, $this->helloEventListener);
        self::assertCount(1, $this->eventDispatcher->getEventListeners(SayHelloEvent::class));

        // test fail when trying add wrong listener to event
        $this->expectException(UnsupportedEventException::class);
        $this->eventDispatcher->addEventListener(SayHelloEvent::class, $this->goodbyeEventListener);

        // remove events...
        $this->eventDispatcher->removeEvent($this->helloEvent);
        $this->eventDispatcher->removeEvent($this->goodbyeEvent);

        // ... and check they're really clean now
        self::assertEquals([], $this->eventDispatcher->getEventListeners(SayHelloEvent::class));
        self::assertEquals([], $this->eventDispatcher->getEventListeners(SayGoodbyeEvent::class));
    }
}
