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

class ArticleController extends Controller
{
    public function auditList()
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
            compact('header', 'description', 'articles', 'options', 'filterValues', 'tableHeaders', 'channels'));
    }
    /**
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
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

    public function channel($id = 1)
    {
        $header = '频道文章';
        $description = '全部';
        $options = [0 => '全部'] + Channel::buildSelectOptions([], 0, str_repeat('&nbsp;', 1));

        //查询条件处理
        $model = new Model(Admin::getModel(Article::class));
        if ($id) {
            $channelIds = array_merge(Channel::branchIds([], $id), [$id]);
            $conditions[] = ['whereIn' => ['channel_id', $channelIds]];
            $model->addConditions($conditions);
        }
        $model->with('articleInfo','author');
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
            compact('header', 'description', 'articles', 'options', 'filterValues', 'tableHeaders'));
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
            $contentPic = json_encode(collect($contentPic)->values()->sortBy('order')->values()->all());
            $request->merge(['content' => $contentPic]);
        }

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
            return redirect()->to('/admin/articles/create')
                ->withInput($request->input())
                ->withErrors($validator->errors());
        }


        $insertFields = [
            'title',
            'type',
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
                $ballotChoices[] = ['content' => $val];
            }
        }


        //dd($ballotChoices);

        //处理文字链接
        $newsLinks = $request->newsLink;
        if ($newsLinks['effective']) {
            $newsLinks = collect($newsLinks)->except('effective')->values()->map(function($value) {
                return [
                    'channel_id' => Tool::getChannelId($value['channels']),
                    'title' => $value['title'],
                ];
            })->all();
        }

//        $exception = DB::transaction(function () use ($article, $keywords, $content, $ballot, $ballotChoices, $newsLinks) {
//            $article->save();
//            $article->keywords()->sync($keywords);
//            $article->content()->save(new Content(['content' => $content]));
//            if ($ballot) {
//                $article->ballot()->save(new Ballot($ballot));
//                foreach ($ballotChoices as $val) {
//                    $choices[] = new BallotChoice($val);
//                }
//                $article->ballot()->first()->choices()->saveMany($choices);
//            }
//            if ($newsLinks) {
//                $link_id = $article->id;
//                foreach ($newsLinks as &$link) {
//                    $link['link_id'] = $link_id;
//                    $link['author_id'] = (int)$article->author_id;
//                    $link['created_at'] = $link['updated_at'] = Carbon::now();
//                    $link['state'] = (int)$article->state;
//                }
//                Article::insert($newsLinks);
//            }
//
//        });

        try {
            $exception = DB::transaction(function () use ($article, $keywords, $content, $ballot, $ballotChoices, $newsLinks) {
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

            });

            $result = is_null($exception) ? true : $exception;

        } catch (\Exception $e) {
            $result = false;
        }

        if ($result) {
            return redirect(Tool::resource())
                ->withSuccess('New article Successfully Created.');
        }

        return redirect()->to('admin/articles/create')->withInput()->withErrors('发表文章失败');
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
            $contentPic = json_encode(collect($contentPic)->values()->sortBy('order')->map(function($val) {
                return collect($val)->only('img', 'title')->all();
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
            'is_soft' => 'in:0,1',
            'is_political' => 'in:0,1',
            'is_international' => 'in:0,1',
            'is_important' => 'in:0,1',
            'publish_at' => 'date',
            'original_url' => 'url',
            'state' => 'in:0,1',
        ];




        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->to('/admin/articles/'.$id)
                ->withInput($request->input())
                ->withErrors($validator->errors());
        }

        $article = Article::with('keywords', 'content')->find($id);

        $canUpdateField = [
            'title',
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
            'original_url',
            'published_at',
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
        $ids = explode(',', $id);
        Article::destroy($ids);
        return Tool::showSuccess('删除成功');
    }

    /**
     * 上线
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function online($id)
    {
        Permission::allow(['administrator', 'responsible_editor']);
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
            if ($article && $article->state == 1 && $article->is_headline == 0) {

                $result = Tool::handleSortLink($article, 'add');
                if ($result) {
                    $article->is_headline = 1;
                    $article->save();
                }
                //dispatch(new SortLinkOrPhoto($article, 'link', 'add'));
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
        $channel_id = Tool::getChannelId(Input::get('channels'));
        $rules = [
            'channel_id' => 'integer|exists:channel,id',
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
    public function link()
    {
        $channel_id = Tool::getChannelId(Input::get('channels'));
        $rules = [
            'channel_id' => 'required|exists:channels,id',
            'title' => 'required|max:50',
            'link_id' => 'required|exists:articles,id,link_id,0',
        ];
        $data = Input::only(['title', 'link_id']);
        $data['channel_id'] = $channel_id;
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return Tool::showError();
        }
        $data['author_id'] = Admin::user()->id;
        $result = Article::create($data);
        if ($result) {
            return Tool::showSuccess('创建链接成功');
        }
        return Tool::showError('创建链接失败');
    }





    /**
     * Create interface.
     *
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
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $header = '编辑文章';
        $description = '描述';
        $article = Article::with('keywords', 'content')->findOrFail($id);
        $keywords = Keyword::pluck('name', 'id');
        $channels = Channel::toTree([], 0);
        if ($article->type == 1) {
            $contentPics = array_values(json_decode($article->content, true));
        }
        $channel_id = $article->channel_id;
        $parentIds = Channel::parentIds($channel_id);
        $links = Article::where('link_id', $id)->get(['id', 'title', 'channel_id'])->map(function($val) {
            return ['id' => $val->id, 'title' => $val->title, 'channel' => Channel::parentIds($val->channel_id)];
        })->all();

        $ballot = $article->ballot;

        $slide = SortLink::where('article_id', $id)->first();
        if ($ballot) {
            $voteOptions = $ballot->choices->map(function($val) {
                return [
                    'id' => $val->id,
                    'option' => $val->content,
                ];
            })->all();
        }

        $initConfig = [
            'coverPic' => $article->cover_pic ? asset('upload/'.$article->cover_pic) : null,
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
