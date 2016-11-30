<?php

namespace App\Api\Controllers;
use App\Api\Transformers\SortLinkTransformer;
use App\SortLink;
use App\SortPhoto;
use Illuminate\Support\Facades\Input;

/**
 * Class ArticleController
 *
 * @package \App\Api\Controllers
 */
class SortLinkController extends BaseController
{

    public function index()
    {
        $links = SortLink::with('article')->orderByRaw('`order` = 0,`order`')->orderBy('created_at')->paginate();
        return $this->paginator($links, new SortLinkTransformer);
    }

    public function withPhotos()
    {
        $links = SortLink::with('article')->orderByRaw('`order` = 0,`order`')->orderBy('created_at')->paginate();
        $links = $this->paginator($links, new SortLinkTransformer);
        if (Input::get('page', 1) <= 1) {
            $photos =  SortPhoto::with('article')->orderByRaw('`order` = 0,`order`')->orderBy('created_at')->get();
            $links->addMeta('photos', $photos->toArray());
        }
        return $links;
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
