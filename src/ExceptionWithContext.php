<?php

declare(strict_types=1);

namespace Exbico\Processor;

use RuntimeException;
use Throwable;

abstract class ExceptionWithContext extends RuntimeException implements ExceptionWithContextInterface
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
        parent::__construct($message, $code, $previous);
    }
}
