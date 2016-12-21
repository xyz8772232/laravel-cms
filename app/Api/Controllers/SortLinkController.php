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
        $links = SortLink::online()->paginate();
        return $this->paginator($links, new SortLinkTransformer);
    }

    public function withPhotos()
    {
        $links = SortLink::online()->paginate();
        $links = $this->paginator($links, new SortLinkTransformer);
        if (Input::get('page', 1) <= 1) {
            $sortPhotos =  SortPhoto::online()->get();
            $photoArticles = $sortPhotos->map(function($sortPhoto) {
                $sortPhoto->article->cover_pic = $sortPhoto->article->cover_pic ? image_url($sortPhoto->article->cover_pic) : null;
                $sortPhoto->article->url = route('articles.show', ['id' => $sortPhoto->article->id]);
                return $sortPhoto->article->toArray();
            })->all();
            $links->addMeta('photos', $photoArticles);
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
