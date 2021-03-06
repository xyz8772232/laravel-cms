<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Tool;
use App\Photo;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{

    public function index()
    {
        $header = '素材';
        $description = '图片列表';
        $photos = Photo::orderBy('id', 'desc')->paginate(12);
        return view('admin.photo.index', ['header' => $header, 'description' => $description, 'photos' => $photos]);
    }

    /**
     * 上传图片
     *
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
        $time = date('Y-m-d H:i:s');
        $add_watermark = (boolean)$request->get('add_watermark', 0);

        //上传图片并加水印
        $photosInfo = collect($photos)->map(function ($photo) use ($uid, $time, $add_watermark) {
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

    public function upload()
    {
        $rules = [
            'photo' => 'required|image',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Tool::showError('图片不符合格式');
        }

        $photo = Input::file('photo');
        $uid = Admin::user()->id;
        $path = app('fileUpload')->prepare($photo);
        $result = Photo::create(['admin_user_id' => $uid, 'path' => $path]);

        if ($result) {
            return Tool::showSuccess('上传成功', ['path' => image_url($path)]);
        }
        return Tool::showError();
    }

    public function batchUpload()
    {
        $rules = [
            'photos.*' => 'required|image',
            'watermark' => 'in:0,1',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Tool::showError('图片不符合格式');
        }

        $uid = Admin::user()->id;
        $watermark = (boolean)Input::get('watermark');

        foreach (Input::file('photos') as $photo) {
            $path = app('fileUpload')->prepare($photo, $watermark);
            Photo::create(['admin_user_id' => $uid, 'path' => $path]);
        }
        return Tool::showSuccess('上传成功');
    }


    public function destroy($id)
    {
        //$this->authorizeForUser(Admin::user(), 'delete', Photo::class);
        Permission::allow(config('admin.admin_editors'));
        $ids = explode(',', $id);
        $result = Photo::clean($ids);
        if ($result) {
            return Tool::showSuccess();
        }
        return Tool::showError();
    }
}
