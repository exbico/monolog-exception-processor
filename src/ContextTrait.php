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
    public function setContext(array $context): static
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @param array<string, mixed> $context
     * @return $this
     */
    public function addContext(array $context): static
    {
        $this->context = array_merge($this->context, $context);
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
