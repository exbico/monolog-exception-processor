<?php

declare(strict_types=1);

namespace Exbico\Processor;

use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Throwable;

/**
 * @phpstan-import-type Record from Logger
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
                $record['extra']['exception'] = $this->getExtraForException($exception);
                $context = array_merge(
                    $this->getContextFromException($exception),
                    $context,
                );
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

        if ($exception instanceof ExceptionWithContextInterface) {
            $result['context'] = $exception->getContext();
        }


        return $result;
    }

    /**
     * @param Throwable $exception
     * @return array<string, mixed>
     */
    private function getContextFromException(Throwable $exception): array
    {
        return array_merge(
            $exception->getPrevious() !== null ? $this->getContextFromException($exception->getPrevious()) : [],
            $exception instanceof ExceptionWithContextInterface ? $exception->getContext() : [],
        );
    }
}
