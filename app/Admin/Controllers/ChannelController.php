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
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\AdminController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class ChannelController extends Controller
{
    use AdminController;

    /**
     * Index interface.
     * @return Content
     */
    public function index()
    {
        $header = '频道';
        $description = 'description';
        $channels = Channel::toTree([], 0);
        dd($channels);

        return view('admin.channel.index', ['header' => $header, 'description' => $description, 'channels' => $channels]);

//        return Admin::content(function(Content $content) {
//          $content->header('header');
//          $content->description('description');
//          $content->body($this->grid());
//      });
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
                Tool::showError('参数错误');
            }
            Channel::saveTree($tree);
            Tool::showSuccess();
        }
        Tool::showError('参数错误');
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }




    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    public function update($id)
    {
        return $this->form()->update($id);
    }

    public function store()
    {
        $parent_id = Input::get('parent_id', 0);
        if (empty($parent_id)) {
            $grade = 0;
        } else {
            $grade = Channel::find($parent_id)->grade + 1;
        }
        Input::merge(['parent_id' => $parent_id, 'grade' => $grade]);

        return $this->form()->store();
    }

    /**
     * 删除,有子频道或频道有文章都不允许，只有在空的状态下才能删除，给个类似的提示：请清空频道或文章后再删除
     * @param $id 文章id
     * @return mixed
     */
    public function destroy($id)
    {
        dd(Channel::toTree());
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

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Channel::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->grade('等级')->sortable();

            $grid->name('频道名');
            $grid->parent_id('父频道')->value(function($channelId) {
                if ($channelId == 0) {
                    return '无';
                }
                return Channel::find($channelId)->name;
            });

            $grid->created_at(trans('admin::lang.created_at'));
            //$grid->updated_at(trans('admin::lang.updated_at'));
//            $grid->filter(function($filter){
//
//                // sql: ... WHERE `user.name` LIKE "%$name%";
//                $filter->like('name', '频道名');
//
//                // sql: ... WHERE `user.email` = $email;
//                $filter->is('parent_id', '父频道');
//
//                // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
//                $filter->between('created_at', trans('admin::lang.created_at'))->datetime();
//            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Channel::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', '频道名');
            $form->select('parent_id', '父频道')->options(Channel::pluck('name', 'id')->prepend('无'));
            $form->hidden('grade');
        });
    }

}
