<?php

namespace Lcarr\CommandRunner\Commands;

class Command
{
    protected function output(string $message): void
    {
        print sprintf("\e[1;37m%s\n", $message);
        flush();
        ob_flush();
    }

    protected function logSuccess(string $message): void
    {
        print sprintf("\e[0;32m%s\n", $message);
        flush();
        ob_flush();
    }

    protected function logError(string $message): void
    {
        print sprintf("\e[0;31m%s\n", $message);
        flush();
        ob_flush();
    }

    protected function read(string $message): string
    {
        return readline($message);
    }

    protected function clear(): void
    {
        print "\e[H\e[J";
    }
}