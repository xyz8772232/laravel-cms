<?php

namespace App\Api\Controllers;
use App\Channel;

/**
 * Class CommentController
 *
 * @package \App\Api\Controllers
 */
class ChannelController extends BaseController
{
    public function index()
    {
        return Channel::toTree();
    }
}
