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

    // 极验-获取流水标识
    $api->get('/geetest', 'Auth\GeetestController@getGeetest');

    // 登录
    $api->post('/login', 'Auth\LoginController@login');

    // 注册-验证极验和发送短信验证码
    $api->post('/signup', 'Auth\RegisterController@smsCode');

    // 完成注册
    $api->post('/register', 'Auth\RegisterController@register');

    // 发送短信验证码
    $api->post('/smscode', 'Auth\RegisterController@smsCode');

    $api->post('/testlogin', 'Test\TestController@login');
    $api->get('/test', 'Test\TestController@refresh');
    // 需要进行身份认证的API
    $api->group(['middleware' => 'jwt'], function($api){

        $api->get('/me', 'Test\TestController@me');

    });
});
