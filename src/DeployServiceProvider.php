<?php

namespace Luischavez\Admin\Deploy;

use Illuminate\Support\ServiceProvider;

class DeployServiceProvider extends ServiceProvider
{

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        Deploy::boot();
    }
}
