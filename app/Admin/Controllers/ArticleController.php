<?php

namespace App\Admin\Controllers;

use App\Article;
use App\Channel;
use App\Keyword;
use App\Tool;
use Encore\Admin\Auth\Permission;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Grid;
use Encore\Admin\Form;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ArticleController extends Controller
{
    /**
     * Store a new article.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:50',
            'type' => 'in:0,1',
            'title_bold' => 'in:0,1',
            'is_headline' => 'in:0,1',
            'content' => 'required'
        ]);

        $insertFields = [
            'title',
            'type',
            'channel',
            'state',
            'is_headline',
            'is_soft',
            'is_political',
            'is_international',
            'is_important',
            'subtitle',
            'title_color',
            'title_bold',
            'description',
            'source',
        ];

        $article = new Article();

        collect($insertFields)->map(function ($field) use ($request, $article) {
            isset($request->$field) && $article->$field = $request->$field;
        });

        //封面图处理
        !empty($request->file('cover_pic')) && $article->cover_pic = $request->file('cover_pic')->store('cover_pic');

        //作者处理
        $article->author_id = Admin::user()->id;

        //内容存储
        $content = $request->get('content');

        //关键字同步
        $keywords = [];
        if (is_array($request->keywords)) {
            $keywords = array_filter($request->keywords);
        }
//        $exception = DB::transaction(function() use ($article, $keywords, $content) {
//            $article->save();
//            $article->keywords()->sync($keywords);
//            $article->content()->save(new \App\Content(['content' => $content]));
//        });
//        dd($article);

        try {
            $exception = DB::transaction(function () use ($article, $keywords, $content) {
                $article->save();
                $article->keywords()->sync($keywords);
                $article->content()->save(new \App\Content(['content' => $content]));
            });

            $result = is_null($exception) ? true : $exception;

        } catch (\Exception $e) {
            $result = false;
        }

        if ($result) {
            return redirect(Tool::resource())
                ->withSuccess('New article Successfully Created.');
        }

        return back()->withInput()->withErrors('发表文章失败');
    }

    /**
     * 更新文章
     *
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @return $this
     */

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:50',
            'type' => 'in:0,1',
            'title_bold' => 'in:0,1',
            'is_headline' => 'in:0,1',
            'content' => 'required'
        ]);
        $article = Article::with('keywords', 'content')->findOrFail($id);

        $canUpdateField = [
            'title',
            'channel',
            'state',
            'is_headline',
            'is_soft',
            'is_political',
            'is_international',
            'is_important',
            'keywords',
            'content',
            'cover_pic',
            'subtitle',
            'title_color',
            'title_bold',
            'description',
            'source',
            'type',
        ];

        $input = collect(Input::only($canUpdateField))->filter(function ($value) {
            return !is_null($value);
        });

        //  更新内容
        if ($input->has('content')) {
            $content = $article->content()->first();
            if ($content->content !== $input->get('content')) {
                $content->content = $input->get('content');
                $content->save();
            }
        }

        //同步关键字
        if ($input->has('keywords')) {
            $article->keywords()->sync($input->get('keywords'));
        }

        //更新封面图
        if ($input->has('cover_pic') && $request->hasFile('cover_pic')) {
            $article->cover_pic = app('fileUpload')->prepare($request->file('cover_pic'));
        }
        $input->except(['content', 'keywords', 'cover_pic'])->map(function ($value, $key) use ($article) {
            $article->$key = $value;
        });

        $result = $article->save();
        if ($result) {
            return redirect(Tool::resource())
                ->withSuccess('更新文章成功');
        }
        return back()->withInput()->withErrors('更新文章失败');
    }

    /**
     * 修改文章属性
     *
     * @param                          $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function change($id, Request $request)
    {
        Permission::allow(['administrator', 'responsible_editor']);
        $this->validate($request, [
            'id' => 'required|exists:articles,id',
        ]);
        $article = Article::find($id);

        $changeableField = [
            'channel',
            'state',
            'is_headline',
            'is_soft',
            'is_political',
            'is_international',
            'is_important',
        ];

        collect(Input::only($changeableField))->filter(function ($value) {
            return !is_null($value);
        })->map(function ($value, $key) use ($article) {
            $article->$key = $value;
        });
        dd($article);
        $result = $article->save();
        if ($request->ajax()) {
            if ($result) {
                return Tool::showSuccess();
            }
            return Tool::showError();
        } else {
            return redirect(Tool::resource());
        }
    }

    /**输出
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Permission::allow(['administrator', 'responsible_editor']);
        $result = Article::findOrFail($id)->delete();
        if ($result) {
            return Tool::showSuccess('删除成功');
        }
        return Tool::showError('删除失败');
        //return redirect()->back()->withInput()->withErrors('删除成功！');
    }

    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '文章列表';
        $description = '描述';
        $options = [0 => '全部'] + Channel::buildSelectOptions([], 0, '&nbsp;&nbsp;');
        $query = Input::all();
        $articles = Article::with('articleInfo', 'author')->where('state', 0)->orderBy('id')->paginate()->appends($query);
        return view('admin.article.index', compact('header', 'description', 'articles', 'options'));
    }

    public function channel($id = 1)
    {
        $options = [0 => '全部'] + Channel::buildSelectOptions([], $id, '&nbsp;&nbsp;');

        $channelIds = Channel::branchIds([], $id);

//        $select = new Form\Field\Select('channel_id', '频道');
//        $select->options($options);
//
//        ob_start();
//
//        echo $select->render();
//
//        $channelSelect = ob_get_contents();
//
//        ob_end_clean();
        //dd($options, $channelSelect);

        $childChannels = Channel::with('children_channel')->find($id)->children_channel->pluck('name', 'id');
        $header = '文章列表';
        $description = '描述';
        $query = Input::except('_pjax');
        $articles =  Article::with('articleInfo', 'author')->whereIn('channel_id', $channelIds )->orderBy('published_at', 'desc')->paginate($query);
        return view('admin.article.index', compact('header', 'description', 'childChannels', 'articles', 'options'));
    }

    /**
     * 建立文字链接
     *
     * @param \Illuminate\Http\Request $request
     */
    public function link(Request $request)
    {
        $this->validate($request, [
            'channel' => 'required|exists:channels,id,grade,4',
            'title' => 'required|max:50',
            'linked_id' => 'required|exists:articles,id,is_link,0',
        ]);
        $channel = $request->get('channel');
        $title = $request->get('title');
        $linked_id = $request->get('linked_id');
        $result = Article::create(['channel' => $channel, 'title' => $title, 'is_link' => 1, 'linked_id' => $linked_id]);
        if ($result) {
            Tool::showSuccess('创建链接成功');
        }
        Tool::showError('创建链接失败');
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        $header = '发表文章';
        $description = '描述';
        $keywords = Keyword::pluck('name', 'id');
        $channels = Channel::toTree([], 0);

        return view('admin.article.create',
            [
                'header' => $header,
                'description' => $description,
                'keywords' => $keywords,
                'channels' => $channels
            ]);
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        $header = '编辑文章';
        $description = '描述';
        $article = Article::with('keywords', 'content')->findOrFail($id);
        $keywords = Keyword::pluck('name', 'id');
        return view('admin.article.edit', ['header' => $header, 'description' => $description, 'article' => $article, 'keywords' => $keywords]);
    }

    /**
     * show interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function show($id)
    {
        $header = '查看文章';
        $description = '描述';
        $article = Article::with('keywords', 'content', 'articleInfo')->findOrFail($id);
        $keywords = Keyword::pluck('name', 'id');
        return view('admin.article.show', ['header' => $header, 'description' => $description, 'article' => $article, 'keywords' => $keywords]);
    }
}
