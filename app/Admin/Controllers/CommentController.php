<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\AdminController;

class CommentController extends Controller
{
    use AdminController;

    public function index() {
        return 1;
    }
}
