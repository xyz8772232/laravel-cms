<?php

namespace App;

use Illuminate\Support\Facades\Auth;
/**
 * Class Permission
 *
 * @package \App
 */
class Permission
{
    /**
     * Check permission.
     *
     * @param $permission
     */
    public static function check($permission)
    {
        if (Auth::guard('admin')->user()->cannot($permission)) {
            static::error();
        }
    }

    /**
     * Roles allowed to access.
     *
     * @param $roles
     */
    public static function allow($roles)
    {
        if (!Auth::guard('admin')->user()->isRole($roles)) {
            static::error();
        }
    }

    /**
     * Roles denied to access.
     *
     * @param $roles
     */
    public static function deny($roles)
    {
        if (Auth::guard('admin')->user()->isRole($roles)) {
            static::error();
        }
    }

    /**
     * Send error response page.
     *
     * @param \Exception $e
     */
    protected static function error()
    {
//        $content = Admin::content(function ($content) {
//            $content->body(view('admin::deny'));
//        });
//
//        response($content)->send();
//        exit;
        Tool::showError('无权限')->send();
        exit;
    }
}
