<?php

namespace App\Admin\Controllers;

use App\SortLink;
use App\Tool;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class SortLinkController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $header = '热推文章排序';
        $description = '';
        $links = SortLink::with('article')->orderByRaw('`order` = 0,`order`')->orderBy('created_at')->get();
//        foreach ($links as $link) {
//            if (empty($link->article)) {
//                dump($link->id);
//            }
//        }
//        exit;
        //dd($links);
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
