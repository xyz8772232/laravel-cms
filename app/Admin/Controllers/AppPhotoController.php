<?php

namespace App\Admin\Controllers;

use App\AppPhoto;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;

class AppPhotoController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        $header = 'APP';
        $description = '启动幻灯片';
        $initPhotos= [];
        $photos =  AppPhoto::orderByRaw('`order` = 0,`order`')->get()->map(function($photo) use(&$initPhotos) {
            $initPhotos[$photo['order']] = [
                'url' => cms_local_to_web($photo['path']),
                'title' => basename($photo['path']),
            ];
        });

        $initConfig = [
            'photos' => $initPhotos,
        ];
        JavaScriptFacade::put([
            'INIT_CONFIG' => $initConfig,
        ]);
        return view('admin.app.photo', compact('header', 'description', 'photos'));
    }

    public function store()
    {
        foreach (range(1, 3) as $val) {
            if ($photo = Input::file('photo'.$val)) {
                AppPhoto::upload($photo, $val);
            } else {
                if (empty(Input::get('old_photo_'.$val))) {
                    AppPhoto::where('order', $val)->delete();
                }
            }
        }
        return redirect(route('admin.app_photos.index'))->withSuccess('更新成功');
    }
}
