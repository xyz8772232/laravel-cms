<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');

$router->resource('channels', ChannelController::class);
$router->resource('articles', ArticleController::class);
