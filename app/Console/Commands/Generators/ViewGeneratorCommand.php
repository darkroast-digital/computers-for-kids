<?php

namespace App\Console\Commands\Generators;

use App\Console\Commands;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Console\Traits\Generatable;

class ViewGeneratorCommand extends Commands
{
    use Generatable;

    /**
     * The command name.
     * 
     * @var string
     * 
     */
    protected $command = 'make:view';

    /**
     * The command description.
     * 
     * @var string
     * 
     */
    protected $description = 'Generate a view.';

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
        if ($this->option('makeController')) {
            dump($this->option('makeController'));
            die;
        }

        $modelBase = __DIR__ . '/../../../../resources/views';
        $path = $modelBase . '/';

        $fileParts = explode('\\', $this->argument('name'));
        $fileName = array_pop($fileParts);

        $cleanPath = implode(('/'), $fileParts);

        if (count($fileParts) >= 1) {
            $path = $path . $cleanPath;

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        $target = $path . '/' . $fileName . '.twig';

        if (file_exists($target)) {
            return $this->error('View already exists!');
        }

        $stub = $this->generateStub('view', [
            'DummyTitle' => $fileName
        ]);

        file_put_contents($target, $stub);

        $this->info('View created!');
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
            ['name', InputArgument::REQUIRED, 'The name of the view to generate.']
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
            ['makeController', 'c', InputOption::VALUE_OPTIONAL, 'Make a controller along with view', null],
        ];
    }
}
