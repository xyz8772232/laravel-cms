<?php

namespace App\Admin\Controllers;

use App\Article;
use App\ArticleInfo;
use App\Ballot;
use App\BallotChoice;
use App\Channel;
use App\Content;
use App\Filter;
use App\Model;
use App\Keyword;
use App\SortLink;
use App\Tool;
use App\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class ArticleController extends Controller
{
    public function auditList()
    {
        $header = '待审核文章';
        $description = '列表';
        $channel_id = (int)Input::get('channel_id', 0);
        $options = [0 => '全部'] + Channel::buildSelectOptions([], 0, str_repeat('&nbsp;', 2));

        //查询条件处理
        $model = new Model(Admin::getModel(Article::class));
        $model->audit();

        if ($channel_id) {
            $channelIds = array_merge(Channel::branchIds([], $channel_id), [$channel_id]);
            $conditions[] = ['whereIn' => ['channel_id', $channelIds]];
            $model->addConditions($conditions);
        }
        $model->with('articleInfo', 'author');
        if (!Input::get('_order')) {
            $model->orderBy('created_at', 'desc');
        }
        //过滤条件
        $filter = new Filter($model);
        $filter->like('title', '标题');
        $filter->between('created_at', trans('admin::lang.created_at'))->datetime();


        //dd($filterValues);
        $articles = $filter->execute();
        $query = Input::all();
        $articles->appends($query);
        //dd($articles->last());
        //dd($filter->conditions(), $filter->execute());

        //$query = Input::all();
        //$articles = Article::with('articleInfo', 'author')->where('state', 0)->orderBy('id', 'desc')->paginate()->appends($query);
        $filterValues = array_filter(Input::only('id', 'title', 'created_at'), function($item) {
            return !is_null($item);
        });
        $filterValues['channel_id'] = $channel_id;
        $tableHeaders = [
            [
                'name' => 'is_important',
                'label' => '重要',
                'sortable' => true,
            ],
            [
                'name' => 'id',
                'label' => 'ID',
                'sortable' => true,
            ],
            [
                'name' => 'title',
                'label' => '标题',
                'sortable' => false,
            ],
            [
                'name' => 'author',
                'label' => '发布者',
                'sortable' => false,
            ],
            [
                'name' => 'published_at',
                'label' => '发布时间',
                'sortable' => true,
            ],
        ];
        return view('admin.article.audit',
            compact('header', 'description', 'articles', 'options', 'filterValues', 'tableHeaders'));
    }
    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function channel()
    {
        $header = '待审核文章';
        $description = '列表';
        $channel_id = (int)Input::get('channel_id', 0);
        $channels = Channel::toTree([], 0);
        $options = [0 => '全部'] + Channel::buildSelectOptions([], 0, str_repeat('&nbsp;', 2));

        //查询条件处理
        $model = new Model(Admin::getModel(Article::class));
        $model->audit();

        if ($channel_id) {
            $channelIds = array_merge(Channel::branchIds([], $channel_id), [$channel_id]);
            $conditions[] = ['whereIn' => ['channel_id', $channelIds]];
            $model->addConditions($conditions);
        }
        $model->with('articleInfo', 'author');
        if (!Input::get('_sort')) {
            $model->orderBy('created_at', 'desc');
        }
        //过滤条件
        $filter = new Filter($model);
        $filter->like('title', '标题');
        $filter->between('created_at', trans('admin::lang.created_at'))->datetime();


        //dd($filterValues);
        $articles = $filter->execute();
        $query = Input::all();
        $articles->appends($query);
        //dd($articles->last());
        //dd($filter->conditions(), $filter->execute());

        //$query = Input::all();
        //$articles = Article::with('articleInfo', 'author')->where('state', 0)->orderBy('id', 'desc')->paginate()->appends($query);
        $filterValues = array_filter(Input::only('id', 'title', 'create_at[start]', 'create_at[end]', 'channel_id'), function($item) {
            return !is_null($item);
        });
        $tableHeaders = [
            [
                'name' => 'is_important',
                'label' => '重要',
                'sortable' => true,
            ],
            [
                'name' => 'id',
                'label' => 'ID',
                'sortable' => true,
            ],
            [
                'name' => 'state',
                'label' => '上线',
                'sortable' => true,
            ],
            [
                'name' => 'title',
                'label' => '标题',
                'sortable' => false,
            ],
            [
                'name' => 'author',
                'label' => '发布者',
                'sortable' => false,
            ],
            [
                'name' => 'published_at',
                'label' => '发布时间',
                'sortable' => true,
            ],
            [
                'name' => 'articleInfo.view_num',
                'label' => '点击量',
                'sortable' => true,
            ],
            [
                'name' => 'articleInfo.comment_num',
                'label' => '评论数',
                'sortable' => true,
            ],
        ];
        return view('admin.article.index',
            compact('header', 'description', 'articles', 'options', 'filterValues', 'tableHeaders', 'channels'));
    }

    public function index()
    {

        $header = '新闻';
        $channel_id = (int)Input::get('channel_id', 1);
        $channel_name = Channel::find($channel_id)->name;
        $description = $channel_name;
        $channels = Channel::toTree([], 0);
        $options = [1 => '新闻'] + Channel::buildSelectOptions([], 1, str_repeat('&nbsp;', 1));

        //查询条件处理
        $model = new Model(Admin::getModel(Article::class));
        $channelIds = array_merge(Channel::branchIds([], $channel_id), [$channel_id]);
        $conditions[] = ['whereIn' => ['channel_id', $channelIds]];
        $model->addConditions($conditions);
        $model->with('articleInfo','author');
        if (!Input::get('_sort')) {
            $model->orderBy('created_at', 'desc');
        }
        //过滤条件
        $filter = new Filter($model);
        $filter->like('title', '标题');
        $filter->between('created_at', trans('admin::lang.created_at'))->datetime();
        $articles = $filter->execute();
        $query = Input::all();
        $articles->appends($query);
        //dd($articles);
        //dd($articles->last());
        //dd($filter->conditions(), $filter->execute());

        $filterValues = array_filter(Input::only('id', 'title', 'created_at'), function($item) {
            return !is_null($item);
        });
        $filterValues['channel_id'] = $channel_id;
        $tableHeaders = [
            [
                'name' => 'is_important',
                'label' => '重要',
                'sortable' => true,
            ],
            [
                'name' => 'id',
                'label' => 'ID',
                'sortable' => true,
            ],
            [
                'name' => 'state',
                'label' => '上线',
                'sortable' => true,
            ],
            [
                'name' => 'title',
                'label' => '标题',
                'sortable' => false,
            ],
            [
                'name' => 'author',
                'label' => '发布者',
                'sortable' => false,
            ],
            [
                'name' => 'published_at',
                'label' => '发布时间',
                'sortable' => true,
            ],
            [
                'name' => 'articleInfo.view_num',
                'label' => '点击量',
                'sortable' => true,
            ],
            [
                'name' => 'articleInfo.comment_num',
                'label' => '评论数',
                'sortable' => true,
            ],
        ];
        return view('admin.article.index',
            compact('header', 'description', 'channels','articles', 'options', 'filterValues', 'tableHeaders'));
    }

    public function preview($id)
    {
        $rules = [
            'id' => 'required|integer|exists:articles,id,state,0',
        ];

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->to('/admin/articles/create')
                ->withInput($request->input())
                ->withErrors($validator->errors());
        }

        $article = Article::find($id);

        return view('admin.article.preview', compact('header', 'description', 'article'));

    }

    /**
     * Store a new article.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //频道处理
        $request->merge(['channel_id' => Tool::getChannelId($request->channels)]);

        //内容存储
        if ($request->type == 1 && $request->contentPic) {
            $contentPic = $request->contentPic;
            $contentPic = json_encode(collect($contentPic)->values()->sortBy('order')->map(function($value) {
                return [
                    'img' => cms_web_to_local($value['img']),
                    'title' => $value['title'],
                ];
            })->all());
            $request->merge(['content' => $contentPic]);
        }
        //dd($request->all());

        $rules = [
            'title' => 'required|max:50',
            'content' => 'required',
            'type' => 'in:0,1',
            'title_bold' => 'in:0,1',
            'is_headline' => 'in:0,1',
            'is_soft' => 'in:0,1',
            'is_political' => 'in:0,1',
            'is_international' => 'in:0,1',
            'is_important' => 'in:0,1',
            'publish_at' => 'date',
            'original_url' => 'url',
            'channel_id' => 'required|integer|exists:channels,id',
        ];




        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->to('/admin/articles/create?channel_id='.$request->channel_id)
                ->withInput($request->input())
                ->withErrors($validator->errors());
        }


        $insertFields = [
            'title',
            'type',
            'is_soft',
            'is_political',
            'is_international',
            'is_important',
            'subtitle',
            'title_color',
            'title_bold',
            'description',
            'source',
            'original_url',
            'published_at',
            'channel_id',
        ];

        $article = new Article();

        collect($insertFields)->map(function ($field) use ($request, $article) {
            isset($request->$field) && $article->$field = $request->$field;
        });

        $content = $request->content;




        //封面图处理
        if (!empty($request->file('cover_pic'))) {
            $coverFile = $request->file('cover_pic');
            $coverName = uniqid('cover_').'.'.$coverFile->guessExtension();
            $article->cover_pic = $coverFile->storeAs('cover_pic', $coverName, 'admin');
        }

        //作者处理
        $article->author_id = Admin::user()->id;

        //关键字同步
        $keywords = [];
        if (is_array($request->keywords)) {
            $keywords = array_filter($request->keywords);
        }
        //pk或投票同步
        $pk = $request->pk;
        $vote = $request->vote;

        $ballot = $ballotChoices = [];
        if (!empty($pk['effective'])) {
            $ballot = ['title' => $pk['title'], 'type' => 2];
            foreach ($pk['options'] as $val) {
                $ballotChoices[] = ['content' => $val];
            }
        } elseif (!empty($vote['effective'])) {
            $ballot = ['title' => $vote['title'], 'type' => $vote['type'], ];
            if ($vote['type'] == 2 && $vote['limit'] > 1) {
                $ballot['max_num'] = $vote['limit'];
            }
            foreach ($vote['options'] as $val) {
                $ballotChoices[] = ['content' => $val['content']];
            }
        }

        //处理文字链接
        $newsLinks = $request->newsLink;
        if ($newsLinks['effective'] ?? false) {
            $newsLinks = collect($newsLinks)->except('effective')->values()->map(function($value) {
                return [
                    'channel_id' => Tool::getChannelId($value['channels']),
                    'title' => $value['title'],
                ];
            })->all();
        }

        try {
            $exception = DB::transaction(function () use ($article, $keywords, $content, $ballot, $ballotChoices, $newsLinks, $request) {

                if (Admin::user()->isRole(config('admin.admin_editors'))) {
                    if ($request->online) {
                        $article->state = 2;
                    }
                }
                $article->save();
                $article->keywords()->sync($keywords);
                $article->content()->save(new Content(['content' => $content]));
                $article->articleInfo()->save(new ArticleInfo());
                if ($ballot) {
                    $article->ballot()->save(new Ballot($ballot));
                    foreach ($ballotChoices as $val) {
                        $choices[] = new BallotChoice($val);
                    }
                    $article->ballot()->first()->choices()->saveMany($choices);
                }
                if ($newsLinks) {
                    $link_id = $article->id;
                    foreach ($newsLinks as &$link) {
                        $link['link_id'] = $link_id;
                        $link['author_id'] = (int)$article->author_id;
                        $link['created_at'] = $link['updated_at'] = Carbon::now();
                        $link['state'] = (int)$article->state;
                    }
                    Article::insert($newsLinks);
                }

                if (Admin::user()->isRole(config('admin.admin_editors'))) {
                    $now = Carbon::now()->toDateTimeString();

                    if ($request->is_headline) {
                        $article->sortLink()->create([
                            'deleted_at' => $now,
                        ]);
                    }

                    if ($request->is_slide) {
                        $article->sortPhoto()->create([
                            'deleted_at' => $now,
                        ]);
                    }
                }

            });

            $result = is_null($exception) ? true : $exception;

        } catch (\Exception $e) {
            $result = false;
        }

        if ($result) {
            return redirect(route('admin.articles.index'))
                ->withSuccess('发表文章成功.');
        }

        return redirect(route('admin.articles.create'))->withInput()->withErrors('发表文章失败');
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
        //内容存储
        if ($request->type == 1 && $request->contentPic) {
            $contentPic = $request->contentPic;
            $contentPic = json_encode(collect($contentPic)->values()->sortBy('order')->map(function($value) {
                return [
                    'img' => cms_web_to_local($value['img']),
                    'title' => $value['title'],
                ];
            })->all());

            $request->merge(['content' => $contentPic]);
        }

        $rules = [
            'id' => 'required|exists:articles,id',
            'title' => 'required|max:50',
            'content' => 'required',
            'type' => 'in:0,1',
            'title_bold' => 'in:0,1',
            'is_headline' => 'in:0,1',
            'is_slide' => 'in:0,1',
            'is_soft' => 'in:0,1',
            'is_political' => 'in:0,1',
            'is_international' => 'in:0,1',
            'is_important' => 'in:0,1',
            'published_at' => 'date',
            'original_url' => 'url',
            'state' => 'in:0,1',
        ];




        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect(route('admin.articles.update', ['id' => $id]))
                ->withInput($request->input())
                ->withErrors($validator->errors());
        }

        $article = Article::with('keywords', 'content')->find($id);

        $updateFields = [
            'title',
            'title_color',
            'title_bold',
            'state',
            'subtitle',
            'description',
            'source',
            'original_url',
            'published_at',
            'is_political',
            'is_international',
            'is_important',
        ];

        collect($updateFields)->map(function ($field) use ($request, $article) {
            isset($request->$field) && $article->$field = $request->$field;
        });


        //封面图处理
        if (!empty($request->file('cover_pic'))) {
            $coverFile = $request->file('cover_pic');
            $coverName = uniqid('cover_').'.'.$coverFile->guessExtension();
            $article->cover_pic = $coverFile->storeAs('cover_pic', $coverName, 'admin');
        } elseif(empty($request->cover_pic_old)){
            $article->cover_pic = null;
        }

        //dd($request->cover_pic);

        //  更新内容
        $content = $article->content()->first();
        $content->content = $request->content;
        $content->save();

        //关键字同步
        $keywords = is_array($request->keywords) ? array_filter($request->keywords) : [];
        $article->keywords()->sync($keywords);


        if (Admin::user()->isRole(config('admin.admin_editors'))) {
            //headline处理
            if ($request->is_headline) {
                Tool::handleSortLink($article, 'add');
            } else {
                Tool::handleSortLink($article, 'delete');
            }

            //slide处理
            if ($request->is_slide) {
                Tool::handleSortPhoto($article, 'add');
            } else {
                Tool::handleSortPhoto($article, 'delete');
            }

            //上线状态处理
            if ($request->online) {
                $article->state = 2;
            }
        }

        $result = $article->save();

        if ($result) {
            return redirect(route('admin.articles.edit', ['id' => $id]))
                ->withSuccess('更新文章成功');
        }
        return redirect(route('admin.articles.edit', ['id' => $id]))->withInput()->withErrors('更新文章失败');
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
        $ids = explode(',', $id);
        Article::destroy($ids);
        return Tool::showSuccess('删除成功');
    }

    /**
     * 审核通过
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function audit($id)
    {
        Permission::allow(['administrator', 'responsible_editor']);
        $ids = explode(',', $id);
        $auditor_id = Admin::user()->id;
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $article = Article::find($id);
            if ($article) {
                $article->state = 2;
                $article->auditor_id = $auditor_id;
                $article->save();
                Tool::handleSortLink($article, 'online');
                Tool::handleSortPhoto($article, 'online');
            }
        }
        return Tool::showSuccess('通过成功');
    }

    /**
     * 提交审核
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function online($id)
    {
        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $article = Article::find($id);
            if ($article) {
                $article->state = 1;
                $article->save();
            }
        }
        return Tool::showSuccess('上线成功');
    }

    /**
     * 设置头条
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function headline($id)
    {
        Permission::allow(['administrator', 'responsible_editor']);
        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $article = Article::find($id);
            if ($article) {
                if ($article->online) {
                    Tool::handleSortLink($article, 'online');
                } else {
                    Tool::handleSortLink($article, 'add');
                }
            }
        }
        return Tool::showSuccess('设置头条成功');
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function transfer($id)
    {
        Permission::allow(['administrator', 'responsible_editor']);
        $serialize = Input::get('channels');
        $channels = json_decode($serialize, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            return Tool::showError('参数错误');
        }
        $channel_id = Tool::getChannelId($channels);
        $rules = [
            'channel_id' => 'integer|exists:channels,id',
        ];
        $validator = Validator::make(['channel_id' => $channel_id], $rules);
        if ($validator->fails()) {
            return Tool::showError('频道不存在');
        }

        $ids = explode(',', $id);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            $article = Article::find($id);
            if ($article) {
                $article->channel_id = $channel_id;
                $article->save();
            }
        }
        return Tool::showSuccess('频道转移成功');
    }

    /**
     * 建立文字链接
     * @return \Illuminate\Http\JsonResponse
     */
    public function link($article_id)
    {
        Permission::allow('administrator', 'responsible_editor');

        $rules = [
            'article_id' => 'integer|exists:articles,id,link_id,0',
        ];
        $validator = Validator::make(['article_id' => $article_id], $rules);
        if ($validator->fails()) {
            return Tool::showError('文章不能被链接');
        }

        if (Input::has('_tree')) {
            $serialize = Input::get('_tree');
            $tree = json_decode($serialize, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                return Tool::showError('参数错误');
            }
            $link_id = $article_id;

            $article = Article::find($article_id);

            foreach ($tree as &$link) {
                $link['link_id'] = $link_id;
                $link['channel_id'] = Tool::getChannelId($link['channels']);
                unset($link['channels']);
                $link['author_id'] = $article->author_id;
                $link['created_at'] = $link['updated_at'] = Carbon::now();
                $link['state'] = $article->state;
            }

            $result = Article::insert($tree);
            if ($result) {
                return Tool::showSuccess('创建链接成功');
            }
            return Tool::showError('创建链接失败');
        }
        return Tool::showError('参数错误');
    }





    /**
     * Create interface.
     *
     */
    public function create()
    {
        $header = '发布新闻';
        $description = '';
        $keywords = Keyword::pluck('name', 'id');
        $channels = Channel::toTree([], 0);
        if ($channel_id = Input::get('channel_id')) {
            $parentIds = Channel::parentIds($channel_id);
        }

        $initConfig =  [
            'status' => 0,
            'channel' => isset($parentIds) ? array_values($parentIds) : null,
        ];

        JavaScriptFacade::put([
            'CHANNEL' => $channels,
            'INIT_CONFIG' => $initConfig,
        ]);

        return view('admin.article.create',
            [
                'header' => $header,
                'description' => $description,
                'keywords' => $keywords,
                'channels' => $channels,
                'initConfig' => $initConfig,
            ]);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $header = '编辑文章';
        $description = '';
        $article = Article::with('keywords', 'content')->findOrFail($id);
        $keywords = Keyword::pluck('name', 'id');
        $channels = Channel::toTree([], 0);
        if ($article->type == 1) {
            $contentPics = collect(json_decode($article->content, true))->map(function($value) {
                return [
                    'img' => cms_local_to_web($value['img']),
                     'title' => $value['title'],
                        ];
            })->all();
        }
        //dd($contentPics);
        $channel_id = $article->channel_id;
        $parentIds = Channel::parentIds($channel_id);
        $links = Article::where('link_id', $id)->get(['id', 'title', 'channel_id'])->map(function($val) {
            return ['id' => $val->id, 'title' => $val->title, 'channel' => Channel::parentIds($val->channel_id)];
        })->all();

        $ballot = $article->ballot;

        $slide = SortLink::where('article_id', $id)->first();
        if ($ballot->vote ?? false) {
            $voteOptions = $ballot->choices->map(function($val) {
                return [
                    'id' => $val->id,
                    'option' => $val->content,
                ];
            })->all();
        }

        if (!empty($article->cover_pic)) {
            $coverPic = [
                'img' => $article->cover_pic ? cms_local_to_web($article->cover_pic) : null,
                'title' => $article->cover_pic ? basename($article->cover_pic) : null,
            ];
        }

        $initConfig = [
            'status' => 1,
            'coverPic' => $coverPic ?? null,
            'contentPics' => $contentPics ?? null,
            'channel' => array_values($parentIds),
            'newsLinks' => $links,
            'voteOptions' => $voteOptions ?? null,
        ];

        return view('admin.article.edit',
            [
            'header' => $header,
            'description' => $description,
            'article' => $article,
            'keywords' => $keywords,
            'channels' => $channels,
            'ballot' => $ballot,
            'slide' => $slide,
            'initConfig' => $initConfig,
            ]
        );
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
