<?php

namespace App\Console\Commands;

use App\Console\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SayHelloCommand extends Commands
{
    /**
     * The command name.
     * 
     * @var string
     * 
     */
    protected $command = 'say:hello';

    /**
     * The command description.
     * 
     * @var string
     * 
     */
    protected $description = 'Say hello';

    /**
     * Handle the command.
     *         
     * @param  InputInterface $input  
     * @param  OutputInterface $output 
     * @return void
     * 
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        for ($i = 0; $i < $this->option('repeat'); $i++) {
            $this->error('Hello ' . $this->argument('name'));
        }
    }

    /**
     * Command arguments.
     * 
     * @return array
     * 
     */
    public function arguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Your name.'],
        ];
    }

    /**
     * Command options.
     * 
     * @return array
     * 
     */
    protected function options()
    {
        return [
            ['repeat', 'r', InputOption::VALUE_OPTIONAL, 'Time to reapeat the output', 1],
        ];
    }
}