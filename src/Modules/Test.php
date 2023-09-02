<?php

namespace Lcarr\CommandRunner\Modules;

use GuzzleHttp\Client;

class Test
{
    public function __construct(private string $name)
    {}

    public function getName(): string
    {
        return $this->name;
    }
}