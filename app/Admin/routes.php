<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');
$router->post('link/{id}', 'ArticleController@link');
$router->get('channels/tree/{id?}', 'ChannelController@tree');
$router->post('channels/save', 'ChannelController@save');


$router->resource('channels', ChannelController::class);

$router->post('articles/{id}/change', 'ArticleController@change');
$router->resource('articles', ArticleController::class);

$router->resource('logs', ArticleLogController::class);
$router->resource('keywords', KeywordController::class);

$router->resource('comments', CommentController::class);
$router->resource('ballots', BallotController::class);

$router->resource('photos', PhotoController::class);
$router->get('watermarks', 'WatermarkController@index');
$router->post('watermarks/save', 'WatermarkController@save');

$router->resource('sort_links', SortLinkController::class);
$router->resource('sort_photos', SortPhotoController::class);

$router->resource('app_photos', AppPhotoController::class);
$router->resource('app_messages', AppMessageController::class);
