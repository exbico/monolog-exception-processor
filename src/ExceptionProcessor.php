<?php

declare(strict_types=1);

namespace Exbico\Processor;

use Monolog\Logger;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Throwable;

final class ExceptionProcessor implements ProcessorInterface
{
    public function __invoke(LogRecord $record): LogRecord
    {
        if (isset($record->context['exception'])) {
            $exception = $record->context['exception'];
            if ($exception instanceof Throwable) {
                $context = array_merge(
                    $this->getContextFromException($exception),
                    $record->context,
                );
                unset($context['exception']);
                $record = new LogRecord(
                    $record->datetime,
                    $record->channel,
                    $record->level,
                    $record->message,
                    $context,
                    $record->extra + ['exception' => $this->getExtraForException($exception)],
                    $record->formatted,
                );
            }
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
