<?php

namespace App\Admin\Controllers;

use App\SortPhoto;
use App\Tool;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SortPhotoController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $header = '首页';
        $description = '幻灯片排序';
        $photos = SortPhoto::online()->get();
        return view('admin.sort.photo', compact('header', 'description', 'photos'));
    }

    /**
     * 保存顺序
     * @return mixed
     */
    public function save()
    {
        if (Input::has('_tree')) {
            $serialize = Input::get('_tree');
            $tree = json_decode($serialize, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                return Tool::showError('参数错误');
            }
            SortPhoto::saveTree($tree);
            return Tool::showSuccess();
        }
        return Tool::showError('参数错误');
    }
}
