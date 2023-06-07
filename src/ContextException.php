<?php

declare(strict_types=1);

namespace Exbico\Formatter;

use RuntimeException;
use Throwable;

abstract class ContextException extends RuntimeException implements ExceptionWithContext
{
    use ContextTrait;

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array<string, mixed> $context
     */
    final public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        array $context = [],
    ) {
        $this->setContext($context);
        if ($previous instanceof self) {
            $this->addContext($previous->getContext());
        }
        parent::__construct($message, $code, $previous);
    }
}
