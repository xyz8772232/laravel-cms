<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Comment;

class CommentController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        Admin::script($this->script());
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
     * 屏蔽
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     */
    public function block($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->blocked = 1;
        $comment->save();
        return response()->json([
            'status'  => true,
            'message' => '屏蔽成功',
        ]);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Comment::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->article_id('文章ID');
            $grid->content('内容');
            $grid->created_at(trans('admin::lang.created_at'));
            $grid->ip('评论者IP');
            $grid->user_id('评论者ID');
            $grid->user_nick('评论者昵称');

            $grid->model()->orderBy('id', 'desc');

            $grid->rows(function($row) {
                $row->actions('delete')->add(function($row) {
                    if (!$row->blocked) {
                        return "<a href=\"javascript:void(0);\" data-id=\"$row->id\" class=\"_block\"><i class='fa fa-chain'></i></a>";
                    } else {
                        return "<i class='fa fa-chain blocked'></i>";
                    }
                });
            });

            $grid->filter(function($filter) {
                $filter->is('article_id', '文章id');
                $filter->like('content', '内容');
                $filter->between('created_at', trans('admin::lang.created_at'))->datetime();
            });

            $grid->disableCreation();
            $grid->disableExport();
            $grid->disablePerPageSelector();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Comment::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('article_id');
            $form->textarea('content');
            $form->display('ip', 'IP');
            $form->text('user_id');
            $form->text('user_nick');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    /**
     * Js code for grid.
     *
     * @return string
     */
    protected function script()
    {
        $token = csrf_token();

        return <<<EOT
$('._block').click(function() {
    var id = $(this).data('id');
    if(confirm("确认屏蔽?")) {
        $.post('/admin/comments/block/' + id, {'_token':'{$token}'}, function(data){

            if (typeof data === 'object') {
                if (data.status) {
                    noty({
                        text: "<strong>Succeeded!</strong><br/>"+data.message,
                        type:'success',
                        timeout: 1000,
                        callback: {
                            onShow: function() {},
                            afterShow: function() {},
                            onClose: function() {},
                            afterClose: function() {
                                location.reload();
                            },
                            onCloseClick: function() {
                                location.reload();
                            },
                        },
                    });
                } else {
                    noty({
                        text: "<strong>Failed!</strong><br/>"+data.message,
                        type:'error',
                        timeout: 1000,
                        callback: {
                            onShow: function() {},
                            afterShow: function() {},
                            onClose: function() {},
                            afterClose: function() {
                                location.reload();
                            },
                            onCloseClick: function() {
                                location.reload();
                            },
                        },
                    });
                }
            }

            //$.pjax.reload('#pjax-container');
        });
    }
});
EOT;
    }
}
