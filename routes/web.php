<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Route::get('/', function()
//{
//    $img = Image::make('upload/image/1.png');
//
//    return $img->response('png');
//});
Route::get('/', function () {
    return view('welcome');
});

Route::get('articles/{id}', ['uses' => 'ArticleController@show', 'as' => 'articles.show']);

//Route::resource('comments', CommentController::class);

