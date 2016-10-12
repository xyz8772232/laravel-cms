<?php

namespace App\Admin\Controllers;

use App\Article;
use App\Keyword;
use Encore\Admin\Auth\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    use AdminController;

    /**
     * Store a new article.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'title' => 'required|max:50',
            'type' => 'in:on,off',
            'title_bold' => 'in:0,1',
            'is_headline' => 'in:on,off',
            'content' => 'required'
        ]);

        $article = new Article();
        $article->title = $request->title;
        isset($request->type) && $article->type = $request->type == 'on' ? 1 : 0;;
        isset($request->title_color) && $article->title_color = $request->title_color;
        isset($request->title_bold) && $article->title_bold = $request->title_bold;
        isset($request->subtitle) && $article->subtitle = $request->subtitle;

        isset($request->description) && $article->description = $request->description;
        isset($request->source) && $article->source = $request->source;
        isset($request->is_headline) && $article->is_headline = $request->is_headline == 'on' ? 1 : 0;

        //封面图处理
        !empty($request->file('cover_pic')) && $article->cover_pic = $request->file('cover_pic')->store('cover_pic');

        //内容存储
        $content = $request->get('content');

        //关键字同步
        $keywords = [];
        if (is_array($request->keywords)) {
            $keywords = array_filter($request->keywords);
        }

        try {
        $exception = DB::transaction(function() use ($article, $keywords, $content) {
            $article->save();
            $article->keywords()->sync($keywords);
            $article->content()->save(new \App\Content(['content' => $content]));
        });

            $result =  is_null($exception) ? true : $exception;

        } catch(\Exception $e) {
            $result = false;
        }

        if ($result) {
            return redirect($this->resource())
                ->withSuccess('New article Successfully Created.');
        }

        return back()->withInput()->withErrors('发表文章失败');

//
//        var_dump($result);exit;
//
//        if ($result) {
//            return new JsonResponse('成功');
//        }
//        return new JsonResponse('失败', 500);
//
//        return Admin::form(Article::class, function (Form $form) {
//            $form->switch('type', '图片新闻')->rules('in:on,off');
//            $form->text('title', '标题')->rules('required|max:50');
//            $form->color('title_color', '标题颜色')->default('#ccc');
//            $form->switch('title_bold', '标题粗体');
//            $form->text('subtitle', '副标题');
//            $form->image('cover_pic', '封面图')->rules();
//            $form->multipleSelect('keywords', '关键字')->options(Keyword::all()->pluck('name', 'id'));
//            $form->textarea('description', '内容简介');
//            $form->editor('content', '正文内容');
//            $form->text('source', '信息来源');
//            $form->switch('is_headline', '头条');
//        })->store();
    }

    public function resource()
    {
        $route = app('router')->current();
        $prefix = $route->getPrefix();

        $resource = trim(preg_replace("#$prefix#", '', $route->getUri(), 1), '/').'/';

        return "/$prefix/".substr($resource, 0, strpos($resource, '/'));
    }

    /**输出
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

    }
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
//        $header = '发表文章';
//        $description = '描述';
//        return view('admin.article.create', ['header' => $header, 'description' => $description]);
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
            $grid->column('title', '标题');
            $grid->author_id('作者');
            $grid->created_at(trans('admin::lang.created_at'));
            $grid->updated_at(trans('admin::lang.updated_at'));
            $grid->filter(function($filter){

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('title', '标题');

                // sql: ... WHERE `user.email` = $email;

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
            $form->switch('type', '图片新闻');
            $form->text('title', '标题')->rules('required');
            $form->color('title_color', '标题颜色')->default('#ccc');
            $form->switch('title_font', '标题粗体');
            $form->text('subtitle', '副标题');
            $form->image('cover_pic', '封面图');
            $form->multipleSelect('keywords', '关键字')->options(Keyword::all()->pluck('name', 'id'));
            $form->dateTime('created_at', trans('admin::lang.created_at'));
            $form->textarea('description', '内容简介');
            $form->editor('content', '正文内容');
            $form->text('source', '信息来源');
            $form->switch('is_headline', '头条');
            //$form->slider('slide_position', '幻灯片位置')->options(['max' => 6, 'min' => 1, 'step' => 1]);
        });
    }
}
