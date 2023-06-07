<?php

declare(strict_types=1);

namespace Exbico\Formatter;

use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Throwable;

/**
 * @phpstan-import-type Record from Logger
 * @phpstan-type Message array{message: string, class: string, trace: string, previous: array, exception: ?Throwable}
 */
final class ExceptionProcessor implements ProcessorInterface
{
    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     * @phpstan-param Record $record
     */
    public function __invoke(array $record): array
    {
        if (isset($record['context']['exception'])) {
            $context = $record['context'];
            $exception = $context['exception'];
            if ($exception instanceof Throwable) {
                $record['extra'] = array_merge(
                    $record['extra'],
                    $this->getExtraForException($exception),
                );
                if ($exception instanceof ContextException) {
                    $context = array_merge(
                        $context,
                        $exception->getContext(),
                    );
                }
            }
            unset($context['exception']);
            $record['context'] = $context;
        }
        return $record;
    }

    /**
     * @param Throwable $exception
     * @return array<string, mixed>
     */
    private function getExtraForException(Throwable $exception): array
    {
        $result = [
            'message' => $exception->getMessage(),
            'class'   => $exception::class,
            'trace'   => $exception->getTraceAsString(),
        ];

        if ($exception->getPrevious() !== null) {
            $result['previous'] = $this->getExtraForException($exception->getPrevious());
        }

        return $result;
    }
}
