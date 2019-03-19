<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//首页  laravel
Route::get('/', function () {
    return view('welcome');
});

//时间戳
//Route::get('/',function(){
//    echo date("Y-m-d m:i:s");
//});

/*Route::get('/',function(){
    $pwd=password_hash('123123',PASSWORD_BCRYPT);
    echo $pwd."</br>";
    $res=password_verify('12312','$2y$10$i7aH2smzvScAaRlCZDr7LOMGkoIX9We6Zy6cEF24faOR2GnzXq9c6');
    var_dump($res);
});*/


Route::get('/adduser','User\UserController@add');

//路由跳转
Route::redirect('/hello1','/world1',301);
Route::get('/world1','Test\TestController@world1');

Route::get('hello2','Test\TestController@hello2');
Route::get('world2','Test\TestController@world2');


//路由参数
Route::get('/user/test','User\UserController@test');
Route::get('/user/{uid}','User\UserController@user');
Route::get('/month/{m}/date/{d}','Test\TestController@md');
Route::get('/name/{str?}','Test\TestController@showName');



// View视图路由
Route::view('/mvc','mvc');
Route::view('/error','error',['code'=>40300]);


// Query Builder
Route::get('/query/get','Test\TestController@query1');
Route::get('/query/where','Test\TestController@query2');


//Route::match(['get','post'],'/test/abc','Test\TestController@abc');
Route::any('/test/abc','Test\TestController@abc');


//加密解密
Route::any('/test/encrpt','Test\TestController@encrpt');

//签名
Route::any('/test/sign','Test\TestController@sign');
Route::any('/test/pub','Test\TestController@pub');


//test
Route::post('/test/curl','Test\TestController@curl');

Route::get('/test/test1','Test\TestController@viewTest1');
Route::get('/test/test2','Test\TestController@viewTest2');
Route::get('/test/check_cookie','Test\TestController@checkCookie')->middleware('check.cookie');//中间件测试

//Route::middleware(['log.click'])->group(function(){
//    Route::get('/test/test1','Test\TestController@viewTest1');
//    Route::get('/test/test2','Test\TestController@viewTest2');
//    Route::get('/test/mid1','Test\TestController@mid1');
//    Route::get('/test/check_cookie','Test\TestController@checkCookie')->middleware('check.cookie');//中间件测试
//});

//用户注册
Route::get('/users/reg','User\UserController@reg');
Route::post('/users/reg','User\UserController@doReg');


//登录
Route::get('/users/login','User\UserController@login');
Route::post('/users/login','User\UserController@dologin');

//用户退出
Route::get('/users/logou','User\UserController@logou');


//用户中心
Route::get('/users/center','User\UserController@center')->middleware('check.login.token');



//模板引入静态文件
Route::get('mvc/test1','Mvc\MvcController@test1');

Route::get('mvc/bst','Mvc\MvcController@bst');



//购物车
Route::get('/cart','Cart\CartController@index')->middleware('check.login.token');
Route::get('/cart/add/{goods_id}','Cart\CartController@add')->middleware('check.login.token');//添加购物车
Route::post('/cart/add2','Cart\CartController@add2');//添加购物车
Route::get('/cart/del/{goods_id}','Cart\CartController@del')->middleware('check.login.token');//删除商品
//Route::get('/cart/del2/{goods_id}','Cart\CartController@del2')->middleware('check.login.token');//删除商品
Route::post('/cart/del2','Cart\CartController@dell')->middleware('check.login.token');//删除商品

//商品
Route::get('/goods/list','Goods\GoodsController@goodsList');//商品列表
Route::get('goods/add','Goods\GoodsController@add');//商品添加
Route::get('/goods/detail/{goods_id}','Goods\GoodsController@index');//商品详情


//订单
Route::get('/order/add','Order\OrderController@add');//下单  及生成订单
Route::get('/order/list','Order\OrderController@orderList');//订单号列表

/*

//支付
Route::get('/pay/ment/{order_id}','Pay\IndexController@order')->middleware('check.login.token');//订单支付

*/

//分布式   测试
Route::get('/pay/order','Order\OrderController@pay');


//支付
Route::get('/pay/alipay/test','Pay\AlipayController@test');         //测试
Route::get('/pay/ment/{order_id}','Pay\AlipayController@pay')->middleware('check.login.token');         //订单支付
Route::post('/pay/alipay/notify','Pay\AlipayController@aliNotify');        //支付宝支付 t异步通知回调
Route::get('/pay/alipay/return','Pay\AlipayController@aliReturn');          //支付宝支付 同步通知


Auth::routes();
//登录
Route::get('/home', 'HomeController@index')->name('home');

//文件上传
Route::get('/upload','Goods\GoodsController@uploadIndex');
Route::post('/upload/pdf','Goods\GoodsController@uploadPdf');

//在线订座
Route::get('/movie/seat','Movie\MvController@index');
Route::get('/movie/buy/{id}','Movie\MvController@buy');

//微信
Route::get('/weixin/refresh_token','Weixin\WeixinController@refreshToken');     //刷新token

Route::get('/weixin/test','Weixin\WeixinController@test');
Route::get('/weixin/valid','Weixin\WeixinController@validToken');
Route::get('/weixin/valid1','Weixin\WeixinController@validToken1');
Route::post('/weixin/valid1','Weixin\WeixinController@wxEvent');        //接收微信服务器事件推送
Route::post('/weixin/valid','Weixin\WeixinController@validToken');

Route::get('/weixin/create_menu','Weixin\WeixinController@createMenu');     //创建菜单
Route::get('/weixin/group','Weixin\WeixinController@textGroup');     //前台发送消息


Route::get('/form/show','Weixin\WeixinController@formShow');     //表单测试
Route::post('/form/test','Weixin\WeixinController@formTest');     //表单测试


Route::get('/weixin/material/list','Weixin\WeixinController@materialList');     //获取永久素材列表
Route::get('/weixin/material/upload','Weixin\WeixinController@upMaterial');     //上传永久素材
Route::post('/weixin/material','Weixin\WeixinController@materialTest');     //创建菜单

Route::get('/weixin/kefu/chat','Weixin\WeixinController@chatView');           //客服
Route::get('/weixin/chat/get_msg','Weixin\WeixinController@getChatMsg');           //私聊
Route::post('/weixin/chat/get_msg','Weixin\WeixinController@msg');           //私聊

//微信支付
Route::get('/weixin/pay/test/{order_id}','Weixin\PayController@test');        //微信支付测试
Route::post('/weixin/pay/notice','Weixin\PayController@notice');      //微信支付通知回调
Route::get('/weixin/pay/wxsuccess','Weixin\PayController@qrwx');      //微信支付视图层


//Route::get('/weixin/login','Weixin\WeixinController@WeixinLogin');      //微信登录视图层
Route::get('/weixin/getcode','Weixin\WeixinController@getCode');      //接收code

Route::get('/weixin/jssdk','Weixin\WeixinController@jssdkTest');        //微信jssdk






//考试
Route::get('/weixin/ks/token/','Weixin\KsController@getAccessToken');
Route::get('/weixin/ks/user/{openid}','Weixin\KsController@getUserInfo');
Route::get('/weixin/ks/userlist','Weixin\KsController@userList');
Route::get('/weixin/ks/black/{id}','Weixin\KsController@black');
Route::get('/weixin/ks/tags/','Weixin\KsController@getWxTags');
Route::get('/weixin/ks/create_tag/','Weixin\KsController@createWxTag');




