<?php

namespace TimWassenburg\ServiceGenerator\Console;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeController extends ControllerMakeCommand
{
    /**
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('service')) {
            $this->createService();
        }
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createService()
    {
        $nameInput = Str::replace('Controller', '', $this->getNameInput());
        $name = Str::studly($nameInput);

        $this->call('make:service', [
            'name' => "{$name}Service"
        ]);
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        $options = parent::getOptions();
        $options[] = ['service', 's', InputOption::VALUE_NONE, 'Generate a service for the controller'];

        return $options;
    }
}
