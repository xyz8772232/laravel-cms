<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');
$router->post('link/{id}', 'ArticleController@link');

$router->resource('channels', ChannelController::class);
$router->resource('articles', ArticleController::class);
$router->resource('keywords', KeywordController::class);
$router->resource('comments', CommentController::class);
$router->resource('photos', PhotoController::class);
$router->resource('logs', ArticleLogController::class);
