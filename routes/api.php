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

    'namespace'  => 'App\Http\Controllers\Api',
    'middleware' => 'serializer:array'

], function($api) {

    // 退出登录
    $api->delete('/logout', 'Auth\LoginController@logout');

    // 完成注册
    $api->post('/register', 'Auth\RegisterController@register');

    // 极验-获取流水标识
    $api->get('/geetest', 'Auth\GeetestController@getGeetest');

    // 解绑手机时检测验证码
    $api->post('/user/checkSms', 'Auth\UserController@checkSmsCode');

    // 解绑邮箱时检测验证码
    $api->post('/user/checkEmailCode', 'Auth\UserController@checkEmailCode');

    $api->group(['middleware' => 'api.throttle', 'limit' => 6, 'expires' => loginExpiresTime(\Request::instance())], function ($api) {
        // 登录
        $api->post('/login', 'Auth\LoginController@login');
    });

    $api->group(['middleware' => 'api.throttle', 'limit' => 10, 'expires' => 1], function ($api) {
        // 注册-验证极验和发送短信验证码
        $api->post('/signup', 'Auth\RegisterController@smsCode');

        $api->group(['middleware' => 'jwt'], function ($api) {
            // 绑定手机时发送短信验证码
            $api->post('/user/bindPhoneSmscode', 'Auth\UserController@smsCode');

            // 解绑手机时发送短信验证码
            $api->post('/user/unbindPhoneSmscode', 'Auth\UserController@smsCode');

            // 绑定邮箱时发送验证码
            $api->post('/user/bindEmailCode', 'Auth\UserController@emailCode');

            // 解绑邮箱时发送验证码
            $api->post('/user/unbindEmailCode', 'Auth\UserController@emailCode');
        });
    });

    // 1分钟只能调一次
    $api->group(['middleware' => 'api.throttle', 'limit' => 10, 'expires' => 1], function ($api) {
        // 发送短信验证码
        $api->post('/smscode', 'Auth\RegisterController@smsCode');
    });

    // 一分钟只能调60次
    $api->group(['middleware' => 'api.throttle', 'limit' => 60, 'expires' => 1], function ($api) {

        // 需要进行身份认证的API
        $api->group(['middleware' => 'jwt'], function($api){
            // 尝试登录
            $api->post('/trylogin', 'Auth\LoginController@tryLogin');

            // 获取个人信息
            $api->get('/me', 'Auth\UserController@me');

            // 修改个人信息
            $api->patch('/user/profile', 'Auth\UserController@update');

            // 绑定手机
            $api->patch('/user/bindPhone', 'Auth\UserController@update');

            // 解绑手机
            $api->patch('/user/unbindPhone', 'Auth\UserController@update');

            // 绑定邮箱
            $api->patch('/user/bindEmail', 'Auth\UserController@update');

            // 解绑邮箱
            $api->patch('/user/unbindEmail', 'Auth\UserController@update');

            // 修改密码
            $api->patch('/user/updatePassword', 'Auth\UserController@update');

            // 修改头像，上传头像
            $api->post('/user/avatar', 'Auth\UserController@update');

            // 修改头像，换一换
            $api->patch('/user/changeAvatar', 'Auth\UserController@update');

            // 获取未读消息
            $api->get('/unread', 'Mess\MessController@getUnread');

            // 标记为已读消息
            $api->post('/markread', 'Mess\MessController@markRead');

            // 全部标记为已读
            $api->post('/markAll', 'Mess\MessController@markAll');

            // 获取已读消息
            $api->get('/read', 'Mess\MessController@getRead');

            // 将已读消息扔进回收站
            $api->patch('/remread', 'Mess\MessController@toRecycle');

            // 全部扔进回收站
            $api->patch('/removeAll', 'Mess\MessController@removeAll');

            // 获取回收站的消息
            $api->get('/recyle', 'Mess\MessController@getRecycle');

            // 回收站还原消息
            $api->patch('/restore', 'Mess\MessController@restoreRecycle');

            // 清除一条消息
            $api->patch('/emptyOne', 'Mess\MessController@emptyOne');

            // 清空回收站
            $api->patch('/emptyAll', 'Mess\MessController@emptyAll');

            // 测试
            $api->get('/testme', 'Test\TestController@me');
        });
    });

    // 测试
    $api->post('/testlogin', 'Test\TestController@login');
});
