<?php

namespace Exbico\Formatter;

interface ExceptionWithContext
{
    /**
     * @return array<string, mixed>
     */
    public function getContext(): array;
}
