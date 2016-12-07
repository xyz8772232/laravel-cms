<?php

namespace App\Admin\Controllers;

use App\AppPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Tool;

class AppPhotoController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $header = 'APP';
        $description = '启动幻灯片';
        $photos = AppPhoto::orderBy('order', 'asc');
        return view('admin.app.photo', compact('header', 'description', 'photos'));
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * 上传
     * @param Request $request
     *
     * @return mixed
     */
    public function upload(Request $request)
    {
        $this->validate($request, [
            'photo' => 'required|image',
            'order' => 'required|in:1,2,3',]);

        $photo = $request->file('photo');
        $uid = Admin::user()->id;
        $path = app('fileUpload')->prepare($photo);
        $order = $request->order;
        $previous = AppPhoto::where('order', $order)->first();
        if ($previous) {
            $previous->path = $path;
            $previous->created_at = Carbon::now();
            $result = $previous->save();
        } else {
            $result = AppPhoto::create(['admin_user_id' => $uid, 'path' => $path, 'order' => $order]);
        }

        if ($result) {
            return Tool::showSuccess('上传成功', ['path' => cms_local_to_web($path)]);
        }
        return Tool::showError();
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

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(AppPhoto::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(AppPhoto::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
