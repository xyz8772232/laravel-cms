<?php

namespace App\Admin\Controllers;

use App\Ballot;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;

class BallotController extends Controller
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

            $content->header('系统');
            $content->description('投票');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    public function store()
    {

    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Ballot::class, function (Grid $grid) {

            //$grid->id('ID')->sortable();
            $grid->article_id('文章id');
            $grid->type('种类')->value(function($id) {
                $names = ['投票-单选','投票-多选', 'PK'];
                return $names[$id];
            });

            $grid->status('状态')->value(function($id) {
                $names = ['进行中', '未开始', '已结束'];
                return $names[$id];
            });

            $grid->start_at('开始时间');
            $grid->end_at('结束时间');
            $grid->result('结果')->value(function() {
                $ballot_id = $this->id;
                $ballot = Ballot::find($ballot_id);
                $choiceStr = $ballot->result()->map(function($choice) {
                    return '<div><span class="vote-percent btn btn-xs btn-info" style="width:50px; margin-right: 5px;">'.$choice['approve_num'].'</span><span class="vote-num btn btn-xs btn-danger" style="width:35px">'.$choice['approve_percent'].'</span>
            <span class="vote-words btn btn-xs">'.$choice['content'].'</span></div>';
                })->all();
                return implode('', $choiceStr);
            });

            $grid->created_at(trans('admin::lang.created_at'));

            $grid->filter(function($filter) {
                $filter->disableIdFilter();
                $filter->is('article_id', '文章id');
            });

            $grid->disableExport();
            $grid->disableBatchDeletion();
            $grid->disableActions();
            $grid->disableCreation();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Ballot::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
