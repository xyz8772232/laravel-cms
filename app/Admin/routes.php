<?php

$router = app('admin.router');

$router->get('/', 'HomeController@index');

//$router->resource('users', UserController::class);
