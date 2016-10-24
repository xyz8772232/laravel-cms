<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Controllers\AdminController;
use App\Photo;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;


class PhotoController extends Controller
{
    use AdminController;

    public function index() {
        // 修改指定图片的大小
        //$img = Image::make('upload/image/tumblr_ocmcitG9C81v6dx7ro1_500.jpg');

// 插入水印, 水印位置在原图片的右下角, 距离下边距 10 像素, 距离右边距 15 像素
        //$img->insert('upload/image/watermark.png', 'bottom-right', 15, 10);

// 将处理后的图片重新保存到其他路径
        //$img->save('upload/image/new_avatar.jpg');
        //return $img->response('jpg');
        $header = '图片列表';
        $description = '描述';
        $pageSize = Input::get('pageSize', 20);
        $photos = [];

        return view('admin.photo.index', ['header' => $header, 'description' => $description, 'photos' => $photos, 'pageSize' => $pageSize]);

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
        Input::merge(['admin_user_id' => (string)Admin::user()->id]);

        return $this->form()->store();
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Photo::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->title('标题');
            //dd($grid->path());
//            $grid->path('图像')->value(function($path) {
//                return '<img class="file-preview-image" src="'.config('admin.upload.host').$path.'">';
//            });
            $grid->path('图像')->image();
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
        return Admin::form(Photo::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('title', '标题')->rules('required|unique:photos,title');
            $form->image('path', '图像');
            $form->hidden('admin_user_id');
        });
    }
}
