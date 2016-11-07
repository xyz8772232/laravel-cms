<?php

namespace App\Api\Controllers;
use App\Article;
use Dingo\Api\Routing\Helpers;

/**
 * Class ArticleController
 *
 * @package \App\Api\Controllers
 */
class ArticleController extends BaseController
{
    use Helpers;

    public function index()
    {

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
