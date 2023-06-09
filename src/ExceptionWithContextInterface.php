<?php

namespace Exbico\Processor;

use Throwable;

interface ExceptionWithContextInterface extends Throwable
{
    /**
     * @return array<string, mixed>
     */
    public function getContext(): array;
}
