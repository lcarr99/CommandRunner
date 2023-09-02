<?php

namespace Lcarr\CommandRunner\Commands;

use InvalidArgumentException;
use ReflectionClass;

class CommandFactory
{
    /**
     * @param CommandRegister $commandRegister
     */
    public function __construct(private CommandRegister $commandRegister)
    {}

    /**
     * @param string $name
     * @return Command
     * @throws \ReflectionException
     */
    public function fromName(string $name): string
    {
        foreach ($this->commandRegister->getCommands() as $class) {
            if (!is_subclass_of($class, Command::class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if ($reflection->getProperty('name')->getDefaultValue() === $name) {
                return $class;
            }
        }

        throw new InvalidArgumentException(sprintf('Command %s not found', $name));
    }
}