<?php

namespace Luischavez\Admin\Deploy;

use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Content;

class DeployController
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Deploy');
            $content->description('Deploy list..');

            $content->row(function (Row $row) {
                $row->column(12, new TriggerDeploy());
            });

            $content->body($this->grid());
        });
    }

    public function grid()
    {
        return Admin::grid(DeployModel::class, function (Grid $grid) {
            $grid->id('ID');
            $grid->finished()->display(function ($finished) {
                return $finished ? 'yes' : 'no';
            });
            $grid->error()->display(function ($error) {
                return $error ? 'yes' : 'no';
            });
            $grid->status()->display(function ($status) {
                return $status;
            });
            $grid->created_at();
            $grid->updated_at();

            $grid->disableCreateButton();
            $grid->disablePagination();
            $grid->disableFilter();
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->disableActions();

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('status');
            });

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
            });

            $grid->exporter(null);
        });
    }

    public function trigger()
    {
        DeployTask::dispatch();

        return redirect()->route('deploy.index');
    }

    public function webhook()
    {
        \Log::debug(request());
        $secret = request()->get('secret');

        app()->abort_if($secret != config('admin.extensions.deploy.secret'), 401);

        $this->trigger();
    }
}
