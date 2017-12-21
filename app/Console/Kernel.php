<?php

namespace App\Console;

class Kernel
{
    protected $commands = [
        \App\Console\Commands\SayHelloCommand::class,
        \App\Console\Commands\SayGoodbyeCommand::class,
    ];

    protected $defaultCommands = [
        \App\Console\Commands\Generators\ConsoleGeneratorCommand::class,
        \App\Console\Commands\Generators\ControllerGeneratorCommand::class,
        \App\Console\Commands\Generators\ModelGeneratorCommand::class,
        \App\Console\Commands\Generators\ViewGeneratorCommand::class,
    ];

    public function getCommands()
    {
        return array_merge($this->commands, $this->defaultCommands);
    }
}