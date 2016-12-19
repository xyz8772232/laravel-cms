<?php
/**
 * Created by PhpStorm.
 * User: xiaotie
 * Date: 16/10/8
 * Time: 15:48
 */

namespace app\Admin\Controllers;


use App\Channel;
use App\Tool;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{
    /**
     * Index interface.
     * @return Content
     */
    public function index()
    {
        $header = trans('lang.system');
        $description = trans('lang.channel');
        $channels = Channel::toTree([], 0, true);

        return view('admin.channel.index',
            ['header' => $header, 'description' => $description, 'channels' => $channels]);
    }

    public function tree($id = 0)
    {
        return Channel::toTree([], $id);
    }

    public function save()
    {
        if (Input::has('_tree')) {
            $serialize = Input::get('_tree');
            $tree = json_decode($serialize, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                return Tool::showError('参数错误');
            }
            Channel::saveTree($tree['children'], $tree['id'] ?? 0);
            return Tool::showSuccess();
        }
        return Tool::showError('参数错误');
    }




    /**
     * Create interface.
     * @param $id
     * @return Content
     */

    public function update($id)
    {
        Validator::make(Input::all(), [
            'name' => 'required',
        ])->validate();

        $name = Input::get('name');
        $channel = Channel::findOrFail($id);
        $nameUsed = Channel::where('parent_id', $channel->parent_id)->where('name', Input::get('name'))->first();
        if ($nameUsed) {
            return Tool::showError('该名称已被使用');
        }

        $channel->name = $name;
        $result = $channel->save();

        if ($result) {
            return Tool::showSuccess();
        }

        return Tool::showError();

    }

    public function store()
    {
        $parent_id = Input::get('parent_id', 0);
        if (empty($parent_id)) {
            $grade = 1;
        } else {
            $grade = Channel::find($parent_id)->grade + 1;
        }

        $nameUsed = Channel::where('parent_id', $parent_id)->where('name', Input::get('name'))->first();
        if ($nameUsed) {
            return Tool::showError('该名称已被使用');
        }

        Input::merge(['parent_id' => $parent_id, 'grade' => $grade, 'admin_user_id' => Admin::user()->id ]);

        $channel = Channel::create(Input::all());

        if ($channel) {
            return Tool::showSuccess();
        }

        return Tool::showError();
    }

    /**
     * 删除,有子频道或频道有文章都不允许，只有在空的状态下才能删除，给个类似的提示：请清空频道或文章后再删除
     * @param $id 文章id
     * @return mixed
     */
    public function destroy($id)
    {
        $channel = Channel::findOrFail($id);
        $children_channel = $channel->children_channel->first();
        if ($children_channel) {
            return Tool::showError('请清空子频道后再删除');
        }
        $articles = $channel->articles->first();

        if ($articles) {
            return Tool::showError('请清空频道下的文章后再删除');
        }
        $rs = $channel->delete();
        if ($rs) {
            return Tool::showSuccess();
        }
        return Tool::showError();
    }
}
