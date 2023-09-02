<?php

namespace Lcarr\CommandRunner;

use InvalidArgumentException;
use Lcarr\CommandRunner\Commands\Command;
use Lcarr\CommandRunner\Commands\CommandFactory;
use Lcarr\CommandRunner\Commands\CommandRegister;
use ReflectionClass;
use ReflectionMethod;

class App
{
    private array $container = [
        'arguments' => [],
    ];
    private Command $command;

    /**
     * @param Arguments $arguments
     */
    public function __construct(private Arguments $arguments)
    {
        $this->buildContainer();
        $this->buildCommand();
    }

    /**
     * @throws \ReflectionException
     */
    public function run(): void
    {
        ob_start();
        $reflection = new ReflectionClass($this->command);
        $method = $reflection->getMethod('handle');
        $arguments = $this->buildMethodArguments($this->command::class, $method);
        $method->invokeArgs($this->command, $arguments);
        ob_end_flush();
    }

    private function buildContainer(): void
    {
        $providers = require './src/Providers/providers.php';

        foreach ($providers as $providerClass) {
            $provider = new $providerClass($this);
            $provider->provide();
        }
    }

    private function buildCommand(): void
    {
        $commandName = $this->arguments->ofIndex(1);

        if ($commandName === null) {
            throw new InvalidArgumentException('Please provide a command name');
        }

        $commandRegister = new CommandRegister();
        $commandFactory = new CommandFactory($commandRegister);
        $class = $commandFactory->fromName($commandName);
        $this->command = $this->make($class);
    }

    private function getGiven(string $classPath, string $parameterPath)
    {
        return $this->container['arguments'][$classPath][$parameterPath] ?? null;
    }

    private function getSingleton(string $classPath)
    {
        return $this->container[$classPath] ?? null;
    }

    private function buildMethodArguments(string $class, ReflectionMethod $method): array
    {
        $arguments = [];

        foreach ($method->getParameters() as $parameter) {
            $given = $this->getGiven($class, $parameter->getType()->getName());

            if ($given !== null) {
                $arguments[] = $given;
                continue;
            }

            $singleton = $this->getSingleton($parameter->getType()->getName());

            if ($singleton !== null) {
                $arguments[] = $singleton;
                continue;
            }

            if (!$parameter->getType()->isBuiltin()) {
                $newInstance = $this->make($parameter->getType()->getName());

                if ($newInstance !== null) {
                    $arguments[] = $newInstance;
                    continue;
                }
            }

            if ($parameter->isOptional()) {
                $arguments[] = $parameter->getDefaultValue();
                continue;
            }

            $arguments[] = null;
        }

        return $arguments;
    }

    public function make(string $class): object
    {
        $reflection = new ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        return $constructor !== null ? $reflection->newInstanceArgs(
            $this->buildMethodArguments($class, $constructor)
        ) : $reflection->newInstanceWithoutConstructor();
    }

    public function singleton(string $class, callable $closure): void
    {
        $instance = $closure();

        if ($instance instanceof $class) {
            $this->container[$class] = $instance;
        }
    }

    public function when(string $class, string $needs, callable $provide): void
    {
        $this->container['arguments'][$class][$needs] = $provide();
    }
}