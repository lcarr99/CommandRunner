<?php

namespace Lcarr\CommandRunner\Providers;

use Lcarr\CommandRunner\App;

class Provider
{
    /**
     * @param App $app
     */
    public function __construct(protected App $app)
    {}

    public function provide(): void
    {}
}