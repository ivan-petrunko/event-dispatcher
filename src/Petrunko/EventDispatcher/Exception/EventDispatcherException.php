<?php

declare(strict_types=1);

namespace Petrunko\EventDispatcher\Exception;

class EventDispatcherException extends \RuntimeException
{
    /**
     * {@inheritDoc}
     */
    public function __construct(string $message = '', $code = 500, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
