<?php

namespace Lcarr\CommandRunner\Providers;

use Lcarr\CommandRunner\Commands\TestCommand;
use Lcarr\CommandRunner\Modules\Test;

class TestProvider extends Provider
{
    public function provide(): void
    {
        $this->app->when(TestCommand::class, Test::class, fn () => new Test('Liam'));
    }
}