<?php

namespace App\Admin\Controllers;

use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use App\Http\Controllers\Controller;
use App\Keyword;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Input;

class KeywordController extends Controller
{
    use AdminController;

    /**
     * Index interface.
     * @return Content
     */
    public function index()
    {
        return Admin::content(function(Content $content) {
            $content->header('系统');
            $content->description('关键词');
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

            $content->body(Admin::form(Keyword::class, function (Form $form) {
                $form->text('name', '关键词');
            }));
        });
    }

    public function update($id)
    {
        return $this->form()->update($id);
    }

    public function store()
    {
        if (empty(Input::get('administrate_id'))) {
            Input::merge(['admin_user_id' => (string)Admin::user()->id]);
        }
        return $this->form()->store();
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Keyword::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->name('关键词')->value(function ($name) {
                return "<span class='label label-success'>{$name}</span>";
            });


            $grid->model()->orderBy('id', 'desc');
            $grid->disableExport();
            $grid->perPages([]);


            $grid->created_at(trans('admin::lang.created_at'))->value(function ($date) {
                    return Carbon::parse($date)->diffForHumans();
                });
            $grid->filter(function($filter){
                //$filter->useModal();

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('name', '关键词');

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
        return Admin::form(Keyword::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name', 'name')->rules('required|unique:keywords');
            $form->hidden('admin_user_id');
        });
    }
}
