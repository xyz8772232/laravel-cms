<?php

namespace App\Admin\Controllers;

use App\Article;
use App\Http\Requests;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    /**
     * Index interface.
     * @return Content
     */
    public function index()
    {

        return Admin::content(function(Content $content) {
            $content->header('header');
            $content->description('description');
            $content->body($this->grid());
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


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Article::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->name('频道名');
            $grid->parent_id('父频道')->value(function($channelId) {
                if ($channelId == 0) {
                    return '无';
                }
                return Channel::find($channelId)->name;
            });

            $grid->created_at(trans('admin::lang.created_at'));
            $grid->updated_at(trans('admin::lang.updated_at'));
            $grid->filter(function($filter){

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('name', '频道名');

                // sql: ... WHERE `user.email` = $email;
                $filter->is('parent_id', '父频道');

                // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
                $filter->between('created_at', trans('admin::lang.created_at'))->datetime();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Article::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', '频道名');
            $form->text('parent_id', '父频道名');

            $form->dateTime('created_at', trans('admin::lang.created_at'));
            $form->dateTime('updated_at', trans('admin::lang.updated_at'));
        });
    }
}
