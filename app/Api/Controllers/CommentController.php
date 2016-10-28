<?php

namespace App\Api\Controllers;
use App\Comment;

/**
 * Class CommentController
 *
 * @package \App\Api\Controllers
 */
class CommentController extends BaseController
{
    public function index()
    {
        return Comment::all();
    }
}
