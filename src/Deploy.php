<?php

namespace Luischavez\Admin\Deploy;

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

            $router->post('deploy/trigger', 'Luischavez\Admin\Deploy\DeployController@trigger')->name('deploy-trigger');
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Deploy', 'deploy', 'fa-toggle-on');
        parent::createPermission('Admin Deploy', 'ext.deploy', 'deploy*');
    }
}
