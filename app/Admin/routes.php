<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');

$router->resource('channels', ChannelController::class);
$router->resource('articles', ArticleController::class);
$router->resource('keywords', KeywordController::class);
$router->resource('comments', CommentController::class);
