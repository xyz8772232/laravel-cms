<?php
/**
 * Created by PhpStorm.
 * User: xiaotie
 * Date: 16/10/8
 * Time: 15:48
 */

namespace app\Admin\Controllers;


use App\Channel;
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
    public function index(Request $request)
    {
//        $grade = $request->get('grade', 0);
//        $header = '频道';
//        $description = 'description';
//        $channels = Channel::where('parent_id', $grade);

        return Admin::content(function(Content $content) {
          $content->header('header');
          $content->description('description');
          $content->body($this->grid());
      });
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
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Channel::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

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
            $form->select('parent_id', '父频道')->options(Channel::pluck('name', 'id'));
        });
    }

}