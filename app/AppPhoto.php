<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AppPhoto extends Model
{
    //
    protected $fillable = ['admin_user_id', 'order', 'path'];

    public static function upload($photo, $order)
    {
        $uid = Admin::user()->id;
        $path = app('fileUpload')->prepare($photo);
        $previous = static::where('order', $order)->first();
        if ($previous) {
            $previous->path = $path;
            $previous->created_at = Carbon::now();
            $result = $previous->save();
        } else {
            $result = static::create(['admin_user_id' => $uid, 'path' => $path, 'order' => $order]);
        }
       return (boolean)$result;
    }
}
