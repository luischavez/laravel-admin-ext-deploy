<?php

namespace Luischavez\Admin\Deploy;

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class TriggerDeploy extends AbstractTool
{

    public function render()
    {
        return view('laravel-admin-deploy::tool');
    }
}
