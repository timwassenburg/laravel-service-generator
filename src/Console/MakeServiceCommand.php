<?php

namespace TimWassenburg\ServiceGenerator\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputArgument;

class MakeServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {--methods=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/service.stub';
    }

    /**
     * Get the method stub.
     */
    protected function getMethodStub(): string
    {
        return __DIR__.'/../../stubs/service-method.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Services';
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service'],
            ['methods', InputArgument::OPTIONAL, 'The methods you want to add to the service (separated by a comma)'],
        ];
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $replace = [];
        $replace['{{ methods }}'] = $this->option('methods') ? $this->setMethods() : '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    private function setMethods(): string
    {
        $methods = [];
        $methodArguments = explode(',', $this->option('methods'));

        foreach ($methodArguments as $methodArgument) {
            $methods[] = str_replace('{{ method_name }}', $methodArgument, $this->files->get($this->getMethodStub()));
        }

        return implode(PHP_EOL, $methods);
    }
}
