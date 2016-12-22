<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Watermark extends Model
{
    protected $fillable = ['path', 'admin_user_id', 'status'];
}
