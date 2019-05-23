<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [

    'namespace' => 'App\Http\Controllers\Api'

], function($api) {

    // 退出登录
    $api->delete('/logout', 'Auth\LoginController@logout');

    // 完成注册
    $api->post('/register', 'Auth\RegisterController@register');

    // 极验-获取流水标识
    $api->get('/geetest', 'Auth\GeetestController@getGeetest');

    $api->group(['middleware' => 'api.throttle', 'limit' => 6, 'expires' => loginExpiresTime(\Request::instance())], function ($api) {
        // 登录
        $api->post('/login', 'Auth\LoginController@login');
    });

    $api->group(['middleware' => 'api.throttle', 'limit' => 3, 'expires' => 1], function ($api) {
        // 注册-验证极验和发送短信验证码
        $api->post('/signup', 'Auth\RegisterController@smsCode');
    });

    // 1分钟只能调一次
    $api->group(['middleware' => 'api.throttle', 'limit' => 1, 'expires' => 1], function ($api) {
        // 发送短信验证码
        $api->post('/smscode', 'Auth\RegisterController@smsCode');
    });

    // 一分钟只能调60次
    $api->group(['middleware' => 'api.throttle', 'limit' => 60, 'expires' => 1], function ($api) {

        // 需要进行身份认证的API
        $api->group(['middleware' => 'jwt'], function($api){
            // 尝试登录
            $api->post('/trylogin', 'Auth\LoginController@tryLogin');

            // 测试
            $api->get('/me', 'Test\TestController@me');
        });
    });

    // 测试
    $api->post('/testlogin', 'Test\TestController@login');
});
