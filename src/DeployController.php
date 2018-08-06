<?php

namespace Luischavez\Admin\Deploy;

use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Content;

use Encore\Admin\Grid\Tools\AbstractTool;

class TestTool extends AbstractTool
{

    public function render()
    {
        return view('laravel-admin-deploy::tool');
    }
}

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
                $row->column(12, new TestTool());
            });

            $content->body($this->grid());
        });
    }

    public function grid()
    {
        return Admin::grid(DeployModel::class, function (Grid $grid) {
            $grid->id('ID');
            $grid->status();
            $grid->created_at();
            $grid->updated_at();

            $grid->disableCreateButton();
            $grid->disablePagination();
            $grid->disableFilter();
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->disableActions();
            $grid->orderable();

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
    }
}
