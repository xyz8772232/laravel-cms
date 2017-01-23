<?php

namespace App\Api\Controllers;

use App\Exposure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
/**
 * Class ExposureController
 *
 * @package \App\Api\Controllers
 */
class ExposureController extends BaseController
{

    public function store()
    {
        $rules = [
            'title' => 'required',
            'desc' => 'required',
            'uname' => 'required',
            'pics.*' => 'image',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest(trans('lang.error_params'));
        }
        $title = Input::get('title', '');
        $desc = Input::get('desc', '');
        $link = Input::get('link', '');
        $uname = Input::get('uname', '');
        $contact = Input::get('contact', '');
        $wechat = Input::get('wechat', '');
        //dd(Input::all());

        $pics = Input::file('pics');

        $exposure = [
            'title' => $title,
            'desc' => $desc,
            'link' => $link,
            'uname' => $uname,
            'contact' => $contact,
        ];
        if ($pics) {
            foreach ($pics as $pic) {
                $paths[] = app('fileUpload')->prepare($pic);
            }
            $pic_json_form = json_encode($paths);
            $exposure['pics'] = $pic_json_form;
        }

        if ($wechat) {
            $exposure['wechat'] = $wechat;
        }

        $result = Exposure::create($exposure);
        if ($result) {
            return $result;
        } else {
            return $this->response->errorInternal();
        }
    }
}
