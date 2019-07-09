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
            // 获取是否为管理员
            $api->get('/user/ismanager', 'Auth\UserController@getIsManager');

            // 获取账户是否进行学籍认证
            $api->get('/user/ischeck', 'Auth\UserController@getIsCheck');

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

            // 学籍认证
            $api->post('/user/uploadCertificat', 'Auth\UserController@uploadCertificat');

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

            // 获取登录用户的车票
            $api->get('/authTickets', 'Ticket\TicketController@getAuthTickets');

            //保存前往大学城的车票
            $api->post('/toUniversity', 'Ticket\TicketController@addToUniversity');

            // 保存前往桂花岗的车票
            $api->post('/toOsmanthus', 'Ticket\TicketController@addToOsmanthus');

            // 获取用户订购的车票
            $api->get('/tickets', 'Ticket\TicketController@getBuyTickets');

            // 管理系统账号管理 -- 获取最高管理员账号id
            $api->get('/management/topid', 'Management\UserController@getTopManagemerId');
            // 管理系统账号管理 -- 获取所有账号
            $api->get('/management/accounts', 'Management\UserController@getUsers');
            // 管理系统账号管理 -- 获取特定页码的账号
            $api->get('/management/pageaccounts', 'Management\UserController@getCurrentPagesUsers');
            // 管理系统账号管理 -- 冻结账号
            $api->post('/management/freeze', 'Management\UserController@freezeUser');
            // 管理系统账号管理 -- 解冻账号
            $api->post('/management/unfreeze', 'Management\UserController@unfreezeUser');
            // 管理系统账号管理 -- 删除账号
            $api->delete('/management/unset', 'Management\UserController@unsetUser');
            // 管理系统账号管理 -- 批量删除账号
            $api->delete('/management/batchUnset', 'Management\UserController@batchUnsetUser');
            // 管理系统账号管理 -- 授权管理员
            $api->post('/management/authorization', 'Management\UserController@authorization');
            // 管理系统账号管理 -- 回收管理员授权
            $api->post('/management/unAuthorization', 'Management\UserController@unAuthorization');

            // 管理系统学籍审核 -- 待审核（全部）
            $api->get('/management/noaudits', 'Management\AuditController@getNoAudits');
            // 管理系统学籍审核 -- 待审核（7条数据）
            $api->get('/management/noaudit', 'Management\AuditController@getNoAudit');
            // 管理系统学籍审核 -- 已审核（7条数据）
            $api->get('/management/audit', 'Management\AuditController@getAudit');
            // 管理系统学籍审核 -- 获取特定页待审核
            $api->get('/management/pagesnoaudit', 'Management\AuditController@getPagesNoAudit');
            // 管理系统学籍审核 -- 管理员通过审核
            $api->post('/management/throwaudit', 'Management\AuditController@throwAudit');
            // 管理系统学籍审核 -- 管理员不给通过审核
            $api->post('/management/nothrowaudit', 'Management\AuditController@noThrowAudit');
            // 管理系统学籍审核 -- 获取特定页已审核
            $api->get('/management/pagesaudit', 'Management\AuditController@getPagesAudit');
            // 管理系统学籍审核 -- 获取不能通过审核的原因
            $api->get('/management/nopassreason', 'Management\AuditController@getNoPassReason');
            // 管理系统学籍审核 -- 通过审核（7条）
            $api->get('/management/pass', 'Management\AuditController@getThrowAudit');
            // 管理系统学籍审核 -- 获取特定页通过审核
            $api->get('/management/pagesthrowaudit', 'Management\AuditController@getCurrentPagesThrowAudit');
            // 管理系统学籍审核 -- 未通过审核（7条）
            $api->get('/management/nopass', 'Management\AuditController@getNoThrowAudit');
            // 管理系统学籍审核 -- 获取特定页未通过审核
            $api->get('/management/pagesnothrowaudit', 'Management\AuditController@getCurrentPagesNoThrowAudit');

            // 管理系统车票系统 -- 添加车票
            $api->post('/management/addfares', 'Management\FareController@addFares');
            // 管理系统车票系统 -- 管理车票--获取车票
            $api->get('/management/allfares', 'Management\FareController@getAllFares');
            // 管理系统车票系统 -- 管理车票--获取特定页车票
            $api->get('/management/currentfares', 'Management\FareController@getCurrentFares');
            // 管理系统车票系统 -- 管理车票--删除车票
            $api->delete('/management/unsetfare', 'Management\FareController@deleteFare');
            // 管理系统车票系统 -- 管理车票--激活车票
            $api->patch('/management/activefare', 'Management\FareController@activeFare');
            // 管理系统车票系统 -- 管理车票--车票失效
            $api->patch('/management/unactivefare', 'Management\FareController@unActiveFare');
            // 管理系统车票系统 -- 管理车票--开启/关闭Ticket Office
            $api->patch('/management/setTicketOffice', 'Management\FareController@setTicketOffice');

            // 管理系统统计中心 -- 获取激活的车票
            $api->get('/management/activefares', 'Management\StatisController@getActiveFares');
            // 管理系统统计中心 -- 获取每个起始点车票的订购情况
            $api->get('/management/countfares', 'Management\StatisController@getCountFares');
            // 管理系统统计中心 -- 获取所有用户订购的车票（7条）
            $api->get('/management/buyfares', 'Management\StatisController@getAllBuyFares');
            // 管理系统统计中心 -- 获取所有用户订购的车票(特定页)
            $api->get('/management/currentbuyfares', 'Management\StatisController@getCurrentBuyFares');
            // 管理系统统计中心 -- 搜索
            $api->get('/management/searchfares', 'Management\StatisController@getSearchFares');
            // 管理系统统计中心 -- 搜索(特定页)
            $api->get('/management/currentsearchfares', 'Management\StatisController@getCurrentSearchFares');
            // 管理系统统计中心 -- execl数据（全部）
            $api->get('/management/execlfares', 'Management\StatisController@getExeclFares');
            // 管理系统统计中心 -- execl数据（特定）
            $api->get('/management/execlfare', 'Management\StatisController@getExeclFare');
            // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（7条）
            $api->get('/management/unosfare', 'Management\StatisController@getUnOsFare');
            // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（特定）
            $api->get('/management/currentunosfare', 'Management\StatisController@getCurrentUnOsFare');
            // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（全部）
            $api->get('/management/unosfares', 'Management\StatisController@getUnOsFares');
            // 测试
            $api->get('/testme', 'Test\TestController@me');
        });



        // 获取车票订购系统是否开放
        $api->get('/ticketsOfficeState', 'Ticket\TicketController@getTicketsOfficeState');

        // 获取游客车票
        $api->get('/noAuthTickets', 'Ticket\TicketController@getNoAuthTickets');
    });

    // 测试
    $api->post('/testlogin', 'Test\TestController@login');
});
