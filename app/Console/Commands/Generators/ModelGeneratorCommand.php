<?php

namespace App\Console\Commands\Generators;

use App\Console\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Console\Traits\Generatable;

class ModelGeneratorCommand extends Commands
{
    use Generatable;

    /**
     * The command name.
     * 
     * @var string
     * 
     */
    protected $command = 'make:model';

    /**
     * The command description.
     * 
     * @var string
     * 
     */
    protected $description = 'Generate model.';

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
        $modelBase = __DIR__ . '/../../../Models';
        $path = $modelBase . '/';
        $namespace = 'App\\Models';

        $fileParts = explode('\\', $this->argument('name'));
        $fileName = array_pop($fileParts);

        $cleanPath = implode(('/'), $fileParts);

        if (count($fileParts) >= 1) {
            $path = $path . $cleanPath;

            $namespace = $namespace . '\\' . str_replace('/', '\\', $cleanPath);

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        $target = $path . '/' . $fileName . '.php';

        if (file_exists($target)) {
            return $this->error('Model already exists!');
        }

        $stub = $this->generateStub('model', [
            'DummyClass' => $fileName,
            'DummyNamespace' => $namespace,
        ]);

        file_put_contents($target, $stub);

        $this->info('Model created!');
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
            ['name', InputArgument::REQUIRED, 'The name of the model to generate.']
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