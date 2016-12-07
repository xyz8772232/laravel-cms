<?php

namespace App\Api\Controllers;
use App\Api\Transformers\AppPhotoTransformer;
use App\AppPhoto;

/**
 * Class ArticleController
 *
 * @package \App\Api\Controllers
 */
class AppPhotoController extends BaseController
{

    public function index()
    {
        $photos =  AppPhoto::orderByRaw('`order` = 0,`order`')->get();
        return $this->collection($photos, new AppPhotoTransformer());
    }
}
