<?php

namespace TimWassenburg\ServiceGenerator\Console;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeModel extends ModelMakeCommand
{
    /**
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('all')) {
            $this->input->setOption('service', true);
        }

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
        $name = Str::studly($this->getNameInput());

        $this->call('make:service', [
            'name' => "{$name}Service",
        ]);
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        $options[] = ['service', 'S', InputOption::VALUE_NONE, 'Generate a service for the model'];

        return $options;
    }
}
