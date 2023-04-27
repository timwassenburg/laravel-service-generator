<?php

namespace TimWassenburg\ServiceGenerator;

use Illuminate\Support\ServiceProvider;
use TimWassenburg\ServiceGenerator\Console\MakeModel;
use TimWassenburg\ServiceGenerator\Console\MakeServiceCommand;

class ServiceGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeServiceCommand::class,
                MakeModel::class,
            ]);
        }
    }
}
