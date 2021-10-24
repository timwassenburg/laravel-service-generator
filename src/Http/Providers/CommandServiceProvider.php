<?php

namespace TimWassenburg\ServiceGenerator\Http\Providers;

use Illuminate\Foundation\Providers\ArtisanServiceProvider;
use TimWassenburg\ServiceGenerator\Console\MakeController;
use TimWassenburg\ServiceGenerator\Console\MakeModel;

class CommandServiceProvider extends ArtisanServiceProvider
{
    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerModelMakeCommand()
    {
        $this->app->singleton('command.model.make', function ($app) {
            return new MakeModel($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerControllerMakeCommand()
    {
        $this->app->singleton('command.controller.make', function ($app) {
            return new MakeController($app['files']);
        });
    }
}
