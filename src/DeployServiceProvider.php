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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-admin-deploy');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-admin-deploy');

        Deploy::boot();
    }
}
