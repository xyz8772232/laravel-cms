<?php

namespace App\Api\Controllers;
use App\Api\Transformers\SortPhotoTransformer;
use App\SortPhoto;

/**
 * Class ArticleController
 *
 * @package \App\Api\Controllers
 */
class SortPhotoController extends BaseController
{

    public function index()
    {
        $photos = SortPhoto::online()->get();
        return $this->collection($photos, new SortPhotoTransformer());
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
