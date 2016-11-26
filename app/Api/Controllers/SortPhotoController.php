<?php

namespace App\Api\Controllers;
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
        return SortPhoto::with('article')->orderByRaw('`order` = 0,`order`')->orderBy('created_at')->get();

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
