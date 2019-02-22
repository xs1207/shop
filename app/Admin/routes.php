<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/goods',GoodsController::class);
    $router->resource('/wx/wx_users',WeixinController::class);               //微信用户管理
    $router->resource('/wx/wx_media',WeixinMediaController::class);         //微信素材
    $router->resource('/wx/group',WeixinGroupController::class);            //后台群发
    $router->post('/wx/group', 'WeixinGroupController@textGroup');      //后台群发


    $router->resource('/wx/bkte', WeixinBkteController::class);      //永久素材
    $router->post('/wx/bkte', 'WeixinBkteController@formTest');      //永久素材




});
