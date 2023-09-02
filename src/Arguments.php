<?php

namespace Lcarr\CommandRunner;

class Arguments
{
    /**
     * @var array|null
     */
    private ?array $arguments;

    public function __construct(?array $arguments = null)
    {
        $this->arguments = $arguments ?? $_SERVER['argv'];
    }

    public function ofIndex(int $index): ?string
    {
        return $this->arguments[$index] ?? null;
    }
}