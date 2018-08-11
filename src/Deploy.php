<?php

namespace Luischavez\Admin\Deploy;

use Illuminate\Support\Facades\Route;

use Encore\Admin\Admin;
use Encore\Admin\Extension;

class Deploy extends Extension
{

    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();
        Admin::extend('deploy', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->resource(
                config('admin.extensions.deploy.name', 'deploy'),
                config('admin.extensions.deploy.controller', 'Luischavez\Admin\Deploy\DeployController')
            );

            $router->post('deploy/trigger', 'Luischavez\Admin\Deploy\DeployController@trigger')->name('deploy.trigger');
        });

        Route::post('deploy_webhook', 'Luischavez\Admin\Deploy\DeployController@webhook')->name('deploy.webhook');
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Deploy', 'deploy', 'fa fa-code-fork');
        parent::createPermission('Admin Deploy', 'ext.deploy', 'deploy*');
    }
}
