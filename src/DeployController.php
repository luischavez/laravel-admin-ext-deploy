<?php

namespace Luischavez\Admin\Deploy;

use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
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
            $content->body($this->grid());
        });
    }

    public function grid()
    {
        return Admin::grid(DeployModel::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->status()->sortable();
            $grid->created_at();
            $grid->updated_at();

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('status');
            });

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
            });
        });
    }
}
