<?php

namespace Exbico\Processor;

trait ContextTrait
{
    /**
     * @var array<string, mixed>
     */
    private array $context = [];

    /**
     * @param array<string, mixed> $context
     * @return $this
     */
    protected function setContext(array $context): static
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
