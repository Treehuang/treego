<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Validation\ValidationException;

class GeetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(static::getPathInfo() == '/api/signup') {
            return [
                'phone' => $this->getPhoneRule(),
                'geetest_challenge' => 'required|geetest',
            ];
        }else if(static::getPathInfo() == '/api/smscode'){
            return [
                'phone' => $this->getPhoneRule(),
            ];
        }else {
            return [
                'phone' => $this->getPhoneRule(),
                'username' => [
                    'bail',
                    'required',
                    "regex: /^[1][0-9]{1}-[\x{4e00}-\x{9fa5}]{2,4}-[\x{4e00}-\x{9fa5}]{2,4}$/u",
                ],
                'password' => [
                    'bail',
                    'required',
                    'regex: /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/',
                ],
            ];
        }
    }

    // 获取验证规则
    protected function getPhoneRule() {
        return [
            'bail',
            'required',
            'regex: /^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/',
            'unique:users',
        ];
    }

    // 自定义异常
    protected function failedValidation(Validator $validator)
    {
        if ($this->container['request'] instanceof Request) {
            throw new ValidationHttpException($validator->errors());
        }

        parent::failedValidation($validator);
    }

    public function messages()
    {
        return [
            'geetest' => '验证模块异常,请重新验证',
            'phone.regex' => '手机号码格式错误',
            'phone.unique' => '手机号码已被注册',
            'phone.required' => '手机号码不能为空',
            'username.required' => '用户名不能为空',
            'username.regex' => '用户名格式错误',
            'password.required' => '密码不能为空',
            'password.regex' => '密码格式错误',
        ];
    }
}
