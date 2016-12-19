<?php

namespace App\Admin\Controllers;

use App\SortLink;
use App\Tool;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SortLinkController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $header = '首页';
        $description = '新闻排序';
        $links = SortLink::online()->get();
        return view('admin.sort.link', ['header' => $header, 'description' => $description, 'links' => $links]);
    }

    /**
     * 保存顺序
     * @return \Illuminate\Http\JsonResponse
     */
    public function save()
    {
        if (Input::has('_tree')) {
            $serialize = Input::get('_tree');
            $tree = json_decode($serialize, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                return Tool::showError('参数错误');
            }
            SortLink::saveTree($tree);
            return Tool::showSuccess();
        }
        return Tool::showError('参数错误');
    }
}
