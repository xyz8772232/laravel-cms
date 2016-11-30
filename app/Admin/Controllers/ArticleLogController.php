<?php

namespace App\Admin\Controllers;

use App\Article;
use App\ArticleLog;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\AdminController;

class ArticleLogController extends Controller
{
    use AdminController;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

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

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(ArticleLog::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->article_id('文章id')->value(function ($articleId) {
                if (Article::find($articleId)) {
                    $editUrl = route('admin.articles.edit', $articleId);
                    return "<a href='$editUrl'>$articleId</a>";
                }
                return $articleId;
            });
            $grid->admin_user_id('操作者')->value(function($userId) {
                return Administrator::find($userId)->name;
            });
            $grid->operation('操作')->value(function($id) {
                    return array_flip(config('article.operation'))[$id];
                });

            $grid->created_at('时间')->value(function($date) {
                if (Carbon::now() < Carbon::parse($date)->addDays(1)) {
                    return  Carbon::parse($date)->toDateTimeString();
                }
                return Carbon::parse($date)->diffForHumans();
            });
            //$grid->paginate(2);

//            $grid->rows(function($row){
//
//                //id小于10的行添加style
//                if($row->id < 10) {
//                    $row->style('color:red');
//                }
//
//                //指定列只开启编辑操作
//                if($row->id % 3) {
//                    $row->actions('edit');
//                }
//
//                //指定列添加自定义操作按钮
//                if($row->id % 2) {
//                    $row->actions()->add(function ($row) {
//                        return "<a class=\"btn btn-xs btn-danger\">btn</a>";
//                    });
//                }
//            });


//            $grid->rows(function($row){
//                $row->actions('delete');
//            });
            $grid->disableCreation();
            $grid->disableActions();
            $grid->disableBatchDeletion();
            $grid->model()->orderBy('id', 'desc');

            $grid->filter(function($filter){

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->is('article_id', '文章id');
                $filter->between('created_at', trans('时间'))->datetime();

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
        return Admin::form(ArticleLog::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
        });
    }
}
