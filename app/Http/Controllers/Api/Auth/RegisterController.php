<?php

namespace App\Http\Controllers\Api\Auth;

use \Cache;
use App\Models\User;
use App\Http\Requests\Api\GeetRequest;
use App\Http\Controllers\Api\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Overtrue\EasySms\EasySms;

class RegisterController extends Controller
{
    use RegistersUsers;

    // 发送短信
    public function smsCode(GeetRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        // 生成随机6位数
//        $code = str_pad(random_int(200000, 999999), 6, 3, STR_PAD_LEFT);
//
//        try{
//            $easySms->send($phone, [
//                'content'  => "【tree的小工具】您的验证码是{$code}，请于2分钟内输入。(如非本人操作，请忽略本短信)",
//            ]);
//        }catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
//            $message = $exception->getException('yunpian')->getMessage();
//            return $this->response->errorInternal($message ?: '短信发送异常');    // 500
//        }
        $code = 123456;

        $key = 'verificationCode_' . str_random(15);
        $expirdAt = now()->addMinutes(2);

        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expirdAt);

        return $this->response->array([
            'verify_key' => $key,
            'expired_at' => $expirdAt->toDateTimeString()
        ])->setStatusCode(201);     // 201 Created - 对创建新资源的 POST 操作进行响应
    }

    public function register(GeetRequest $request)
    {
        $verifyData = \Cache::get($request->verify_key);

        // 将信息存入数据库
        $user = $this->create(array_merge($request->all(), $verifyData));

        // 删除缓存的短信验证码
        \Cache::forget($request->verify_key);

        return $this->response->created();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'phone' => $data['phone'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
