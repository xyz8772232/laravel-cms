<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $fillable = ['path', 'admin_user_id'];

    /**
     * @param $ids
     * @return boolean
     */
    public static function clean($ids)
    {
        foreach ($ids as $id) {
            $photo = static::find($id);
            Storage::disk(config('admin.upload.disk'))->delete($photo['path']);
            $photo->delete();
        }
        return true;
    }
}
