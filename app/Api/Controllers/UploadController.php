<?php

namespace App\Api\Controllers;
use Illuminate\Support\Facades\Input;

/**
 * Class UploadController
 *
 * @package \App\Api\Controllers
 */
class UploadController extends BaseController
{
    public function image()
    {
        $file =  Input::file('file');
        $watermark =  Input::get('watermark', 0);
        $path = app('fileUpload')->prepare($file, (bool)$watermark);
        return $path;
    }
}
