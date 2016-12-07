<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppPhoto extends Model
{
    //
    protected $fillable = ['admin_user_id', 'order', 'path'];
}
