<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Watermark;
use Encore\Admin\Auth\Database\Administrator;
use App\Photo;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;


class WatermarkController extends Controller
{
    use ModelForm;

    public function index()
    {
        $header = '系统';
        $description = '水印';
        $watermark = Watermark::find(1);
        $image['path'] = asset('upload/'.$watermark->path);
        $image['caption'] = pathinfo($watermark->path, PATHINFO_BASENAME);
        return view('admin.watermark.index', ['header' => $header, 'description' => $description, 'watermark' => $image]);

//        return Admin::content(function(Content $content) {
//            $content->header('header');
//            $content->description('description');
//            $content->body($this->grid());
//        });
    }

    public function save(Request $request)
    {
        $this->validate($request, [
           'path' => 'required|image'
        ]);

        $file = $request->file('path');
        $uid = Admin::user()->id;

        $path = app('fileUpload')->prepare($file);
        $watermark = Watermark::where('status', 1)->first();
        if ($watermark) {
            $watermark->path = $path;
            $watermark->admin_user_id = $uid;
            $result = $watermark->save();
        } else {
            $result = $watermark->create(['path' => $path, 'admin_user_id' => $uid, 'status' => 1]);
        }

        if (!$result) {
            return back()->withErrors('上传失败');
        } else {
            return back();
        }


//        if ($result) {
//            return Tool::showSuccess();
//        }
//        return Tool::showError();
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

            $content->body(Admin::form(Photo::class, function (Form $form) {
                //$form->display('id', 'ID');
                $form->text('title', '标题');
                $form->image('path', '图像');
            }));
        });
    }

    public function update($id)
    {
        return $this->form()->update($id);
    }

    public function store()
    {
        if (empty(Input::get('administrate_id'))) {
            Input::merge(['administrate_id' => (string)Auth::guard('admin')->user()->id]);
        }
        return $this->form()->store();
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Watermark::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->path('图像')->value(function($path) {
                return '<img style="max-width:200px;max-height:200px" class="img" src="'.asset($path).'">';
            });
            $grid->admin_user_id('上传者')->value(function($id) {
                return Administrator::find($id)->name;
            });

            $grid->created_at(trans('admin::lang.created_at'));
            $grid->filter(function($filter){

                // sql: ... WHERE `user.name` LIKE "%$name%";
                $filter->like('title', '标题');

                // sql: ... WHERE `user.created_at` BETWEEN $start AND $end;
                $filter->between('created_at', trans('admin::lang.created_at'))->datetime();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Watermark::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->image('path', '图像');
            $form->hidden('administrate_id');
        });
    }
}
