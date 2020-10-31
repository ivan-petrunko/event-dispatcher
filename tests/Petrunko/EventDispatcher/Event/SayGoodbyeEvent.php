<?php

declare(strict_types=1);

namespace Tests\Petrunko\EventDispatcher\Event;

use Petrunko\EventDispatcher\Event\EventInterface;

class SayGoodbyeEvent implements EventInterface
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
