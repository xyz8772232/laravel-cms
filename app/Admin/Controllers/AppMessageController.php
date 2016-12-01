<?php

namespace App\Admin\Controllers;

use App\AppMessage;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AppMessageController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $header = 'APP';
        $description = '消息推送';
        return view('admin.app.message', ['header' => $header, 'description' => $description]);

    }

    public function store()
    {
        Input::merge(['admin_user_id' => (string)Admin::user()->id]);
        return $this->form()->store();
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
        return Admin::grid(AppMessage::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->article_id('文章ID')->sortable();
            $grid->content('内容');
            $grid->created_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(AppMessage::class, function (Form $form) {

            $form->textarea('content', '内容')->rules('required');
            $form->text('article_id', '文章id')->rules('required|exists:articles,id,state,2,deleted_at,NULL');
            $form->hidden('admin_user_id', '发布人id');
        });
    }
}
