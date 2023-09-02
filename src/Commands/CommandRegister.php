<?php

namespace Lcarr\CommandRunner\Commands;

class CommandRegister
{
    public function getCommands()
    {
        return [
            TestCommand::class,
        ];
    }
}