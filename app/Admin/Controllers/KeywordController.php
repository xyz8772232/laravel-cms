<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Keyword;
use App\Tool;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\JsonResponse;

class KeywordController extends Controller
{
    /**
     * Index interface.
     * @return Content
     */
    public function index()
    {
        $header = trans('lang.system');
        $description = trans('lang.keyword');
        $keyword = trim(Input::get('keyword', ''));
        $filterValues = [];
        if ($keyword !== '') {
            $keywords = Keyword::where('name', 'like', "%$keyword%")->get();
            $filterValues['keyword'] = $keyword;
        } else {
            $keywords = Keyword::all();
        }

        return view('admin.keyword.index', compact('header', 'description', 'keywords', 'filterValues'));
    }

    public function store()
    {
        $admin_user_id = Admin::user()->id;
        $name = Input::get('name', '');
        if (empty($name)) {
            return Tool::showError('参数错误');
        }
        $keyword = Keyword::where('name', $name)->first();
        if ($keyword) {
            return Tool::showError('已存在');
        }
        $keyword = Keyword::onlyTrashed()->where('name', $name)->first();
        if ($keyword) {
            $result = $keyword->restore();
        } else {
            $result = Keyword::create(['admin_user_id' => $admin_user_id, 'name' => $name]);
        }
        if ($result) {
            return Tool::showSuccess('添加成功');
        } else {
            return Tool::showError('添加失败');
        }
    }

    /**
     * 修改
     * @param $id
     *
     * @return mixed
     */
    public function update($id)
    {
        $admin_user_id = Admin::user()->id;
        $name = Input::get('name', '');
        if (empty($name)) {
            return Tool::showError('参数错误');
        }
        $keyword = Keyword::find($id);
        if (!$keyword || $keyword->name == $name) {
            return Tool::showError('参数错误');
        }

        $duplicateKeyword = Keyword::where('name', $name)->first();
        if ($duplicateKeyword) {
            return Tool::showError('名称已存在');
        }
        $keyword->name = $name;
        $keyword->admin_user_id = $admin_user_id;
        $result = $keyword->save();
        if ($result) {
            return Tool::showSuccess('修改成功');
        } else {
            return Tool::showError('修改失败');
        }
    }


    /**
     * 删除
     * @param $id keyword id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $rs = Keyword::destroy($id);
        if ($rs) {
            return Tool::showSuccess();
        }
        return Tool::showError();
    }
}
