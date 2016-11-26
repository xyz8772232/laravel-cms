<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');

//频道管理相关
$router->get('channels/tree/{id?}', 'ChannelController@tree');
$router->post('channels/save', 'ChannelController@save');
$router->resource('channels', ChannelController::class);

//文章管理相关
$router->get('articles/audit_list', 'ArticleController@auditList');
$router->get('articles/channel/{id?}', ['uses' => 'ArticleController@channel', 'as' => 'articles.channel']);
$router->post('articles/change/{id}', 'ArticleController@change');
$router->post('articles/online/{id}', 'ArticleController@online');
$router->post('articles/audit/{id}', 'ArticleController@audit');
$router->post('articles/headline/{id}', 'ArticleController@headline');
$router->post('articles/transfer/{id}', 'ArticleController@transfer');
$router->post('articles/link/{article_id}', 'ArticleController@link');
$router->get('articles/preview/{id}', ['uses' => 'ArticleController@preview', 'as' => 'articles.preview']);
$router->resource('articles', ArticleController::class);

$router->resource('logs', ArticleLogController::class);
$router->resource('keywords', KeywordController::class);

//Route::group([
//    'prefix'        => config('admin.prefix'),
//    'namespace'     => Admin::controllerNamespace(),
//    'middleware'    => ['web', 'admin.auth', 'admin.pjax'],
//], function ($router) {
//    $router->post('comments/block/{$id}', 'CommentController@block')->name('comments.block');
//});
$router->post('comments/block/{id}', ['uses' => 'CommentController@block', 'as' => 'comments.block']);

$router->resource('comments', CommentController::class);

$router->post('ballots/choice/{$id}', 'BallotController@addChoice');
$router->resource('ballots', BallotController::class);

$router->post('photos/upload', 'PhotoController@upload');
$router->resource('photos', PhotoController::class);
$router->get('watermarks', 'WatermarkController@index');
$router->post('watermarks/save', 'WatermarkController@save');
$router->get('watermarks/create', 'WatermarkController@create');

$router->any('ueditor', 'UploadController@ueditorUpload');

$router->post('sort_links/save', 'SortLinkController@save');
$router->resource('sort_links', SortLinkController::class);

$router->post('sort_photos/save', 'SortPhotoController@save');
$router->resource('sort_photos', SortPhotoController::class);

$router->resource('app_photos', AppPhotoController::class);
$router->resource('app_messages', AppMessageController::class);
