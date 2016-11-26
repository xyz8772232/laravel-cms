<?php

namespace App\Api\Controllers;
use App\Api\Transformers\SortLinkTransformer;
use App\SortLink;

/**
 * Class ArticleController
 *
 * @package \App\Api\Controllers
 */
class SortLinkController extends BaseController
{

    public function index()
    {
        $links = SortLink::with('article')->orderByRaw('`order` = 0,`order`')->orderBy('created_at')->get();
        return $this->collection($links, new SortLinkTransformer());
    }

    public function channel($id)
    {
        return Article::where('channel_id', $id)->get();
    }

    public function show($id)
    {
        return Article::with('content')->find($id);
    }
}
