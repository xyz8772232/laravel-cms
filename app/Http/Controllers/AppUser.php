<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Input;
trait AppUser
{
    protected static $appUser = [];

    protected function getAppUser()
    {
        if (!empty(Request::cookie('uid')) && !empty(Request::cookie('username'))) {
            static::$appUser['uid'] = Request::cookie('uid');
            static::$appUser['username'] = Request::cookie('username');
        } else {
            if (Input::get('from', '') == 'app') {
                static::$appUser['uid'] = Input::get('uid', 0);
                static::$appUser['username'] = Input::get('username', '');
            }
        }
    }
}

