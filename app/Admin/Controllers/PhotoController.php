<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Tool;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Controllers\AdminController;
use App\Photo;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class PhotoController extends Controller
{
    use AdminController;

    public function index() {
        $header = '图片列表';
        $description = '描述';
        $photos = Photo::orderBy('id', 'desc')->paginate(20);
        return view('admin.photo.index', ['header' => $header, 'description' => $description, 'photos' => $photos]);
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

    /**
     * 上传图片
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'photos.*' => 'required|image']);
        $photos = $request->file('photos');
        $uid = Admin::user()->id;
        $time  = date('Y-m-d H:i:s');
        $add_watermark = (boolean)$request->get('add_watermark', 0);

        //上传图片并加水印
        $photosInfo = collect($photos)->map(function($photo) use ($uid, $time, $add_watermark){
            $path = app('fileUpload')->prepare($photo, $add_watermark);
            if ($path) {
                $item = [
                    'path' => $path,
                    'admin_user_id' => $uid,
                    'created_at' => $time,
                    'updated_at' => $time,
                ];
                return $item;
            }
        })->all();

        $result = DB::table('photos')->insert($photosInfo);

        if ($result) {
            return Tool::showSuccess();
        }
        return Tool::showError();
    }


    public function destroy($id) {
        $changeableField = [
            'channel',
            'state',
            'is_headline',
            'is_soft',
            'is_political',
            'is_international',
            'is_important',
        ];


        $this->authorizeForUser(Admin::user(), 'delete', Photo::class);
        //Permission::allow('responsible_editor');
        $result = Photo::destroy($id);
        if ($result) {
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
