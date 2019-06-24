<?php

namespace App\Http\Controllers\Api\Auth;

use Auth;
use \Hash;
use \Cache;
use Illuminate\Http\Request;
use \Mail;
use Naux\Mail\SendCloudTemplate;
use Overtrue\EasySms\EasySms;
use App\Transformers\UserTranformer;
use App\Http\Requests\Api\UserRequest;
use App\Http\Controllers\Api\Controller;

class UserController extends Controller
{
    // 发送短信
    public function smsCode (UserRequest $request, EasySms $easySms) {
        // 用户输入的手机号
        $phone = $request->phone;

        // 解绑时发送短信验证码，需要校验用户输入的手机号是否与账号的手机号是否一致
        if ($request->getPathInfo() === '/api/user/unbindPhoneSmscode') {
            $user = Auth::guard('api')->user();
            if ($phone !== $user->phone) {
                return response()->json([ 'errors' => ['phone' => '输入的手机号与绑定的手机号不符'] ], 422);
            }
        }

        // 生成随机6位数
//        $code = str_pad(random_int(200000, 999999), 6, 3, STR_PAD_LEFT);

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
        $expirsIn = now()->addMinutes(2);

        Cache::put($key, ['phone' => $phone, 'code' => $code], $expirsIn);

        return response()->json([
            'verify_key' => $key,
            'expires_in' => $expirsIn->toDateTimeString()
        ], 201);    // 201 Created - 对创建新资源的 POST 操作进行响应
    }

    // 发送邮箱验证码
    public function emailCode(UserRequest $request) {

        if ($request->getPathInfo() === '/api/user/unbindEmailCode') {
            // 解绑邮箱时发送

            $user = Auth::guard('api')->user();
            $email = $user->email;

            // 校验提交的密码和账号的密码是否一致
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([ 'errors' => ['password' => '输入的原密码与此账号的密码不符'] ], 422);
            }
        }else {
            // 绑定邮箱时发送
            $email = $request->email;
        }

        // 生成随机6位数
        $code = str_pad(random_int(200000, 999999), 6, 3, STR_PAD_LEFT);

        $bind_data = ['emailCode' => $code];
        $template = new SendCloudTemplate('email_code', $bind_data);

        try {
            Mail::raw($template, function ($message) use ($email) {
                $message->from('treehuang@163.com', 'treeGo');
                $message->to($email)->cc('treehuang@163.com');
            });
        }catch (\Exception $exception) {
            return $this->response->errorInternal('邮件发送异常');
        }

        $key = 'verificationCode_' . str_random(15);
        $expirsIn = now()->addMinutes(2);

        Cache::put($key, ['code' => $code], $expirsIn);

        return response()->json([
            'verify_key' => $key,
            'expires_in' => $expirsIn->toDateTimeString()
        ], 201);
    }

    // 校验邮件验证码
    public function checkEmailCode (Request $request) {
        $verifyData = Cache::get($request->verify_key);

        if (!$verifyData) {
            return response()->json(['errors' => ['code' => '验证码已经失效']], 422);
        }

        if (!hash_equals((string)$verifyData['code'], (string)$request->email_code)) {
            return response()->json(['errors' => ['code' => '验证码错误']], 422);
        }

        return $this->response->noContent();
    }


    // 校验短信验证码
    public function checkSmsCode (Request $request) {
        $verifyData = Cache::get($request->verify_key);

        if (!$verifyData) {
            return response()->json(['errors' => ['smscode' => '验证码已经失效']], 422);
        }

        if (!hash_equals((string)$verifyData['code'], (string)$request->smscode)) {
            return response()->json(['errors' => ['smscode' => '验证码错误']], 422);
        }

        return $this->response->noContent();
    }


    // 获取个人信息
    public function me () {
        return $this->response->item(Auth::guard('api')->user(), new UserTranformer());
    }

    // 修改个人信息
    public function update (UserRequest $request) {

        switch ($request->getPathInfo()) {
            case '/api/user/profile' :
                $user = Auth::guard('api')->user();
                $attributes = $request->only(['sex', 'college', 'campus', 'introduction']);
                $attributes['username'] = $request->nickname;
                $user->update($attributes);
                return $this->response->item($user, new UserTranformer());
                break;

            case '/api/user/unbindPhone' :
                $user = Auth::guard('api')->user();
                $attributes['phone'] = null;
                $user->update($attributes);
                return $this->response->item($user, new UserTranformer());
                break;

            case '/api/user/bindPhone' :
                $user = Auth::guard('api')->user();
                $attributes['phone'] = $request->phone;
                $user->update($attributes);
                return $this->response->item($user, new UserTranformer());
                break;

            case '/api/user/updatePassword' :
                $user = Auth::guard('api')->user();

                // 校验提交的密码和账号的密码是否一致
                if (!Hash::check($request->oldPassword, $user->password)) {
                    return response()->json([ 'errors' => ['password' => '输入的原密码与此账号的密码不符'] ], 422);
                }

                $attributes['password'] = bcrypt($request->newPassword);
                $user->update($attributes);
                return $this->response->noContent();
                break;

            case '/api/user/avatar' :

                $user = Auth::guard('api')->user();

                $file = $request->avatar;
                $extension = $file->getClientOriginalExtension();

                if (!$request->hasFile('avatar') || !$file->isValid() || !in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                    return response()->json(['errors' => ['avatar' => '上传图片失败']], 422);
                }

                // 头像存储路径
                $folder_name = '/images/uploads/avatar/' . date("Ym/d", time());
                $upload_path = public_path() . $folder_name;

                $filename =$user->id . '_' . time() . '_' . str_random(10) . '.' . $extension;

                // 将图片移动到目标存储路径中
                $file->move($upload_path, $filename);

                $attributes['avatar'] = $folder_name . '/' . $filename;
                $user->update($attributes);

                return $this->response->item($user, new UserTranformer());

            case '/api/user/changeAvatar' :

                $user = Auth::guard('api')->user();
                $attributes['avatar'] = $request->avatar;

                $user->update($attributes);
                return $this->response->item($user, new UserTranformer());

                break;

            case '/api/user/unbindEmail' :
                $user = Auth::guard('api')->user();
                $attributes['email'] = null;
                $user->update($attributes);
                return $this->response->item($user, new UserTranformer());
                break;

            case '/api/user/bindEmail' :
                $user = Auth::guard('api')->user();
                $attributes['email'] = $request->email;
                $user->update($attributes);
                return $this->response->item($user, new UserTranformer());
                break;
        }

        return 0;
    }

    public function getIsManager() {
        $user = Auth::guard('api')->user();
        return response()->json(['is_manager' => $user->is_manager]);
    }
}
