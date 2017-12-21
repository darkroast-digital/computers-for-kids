<?php

namespace App\Console\Commands\Generators;

use App\Console\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Console\Traits\Generatable;

class ConsoleGeneratorCommand extends Commands
{
    use Generatable;

    /**
     * The command name.
     * 
     * @var string
     * 
     */
    protected $command = 'make:console';

    /**
     * The command description.
     * 
     * @var string
     * 
     */
    protected $description = 'Generate console command.';

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
        $stub = $this->generateStub('command', [
            'DummyClass' => $this->argument('name')
        ]);

        $target = __DIR__ . '/../' . $this->argument('name') . '.php';

        // check if file exists

        if (file_exists($target)) {
            return $this->error('Command already exists');
        }

        file_put_contents($target, $stub);

        return $this->info('Console command generated!');
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
            ['name', InputArgument::REQUIRED, 'The name of the command to generate.']
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
            //
        ];
    }
}