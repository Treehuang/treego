<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\ValidationHttpException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'geetest_challenge' => 'required|geetest',

            'account' => 'bail|required|string',

            'password' => [
                'bail',
                'required',
                'regex: /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/',
            ],
        ];
    }

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
            'account.string' => '账号格式错误',
            'account.required' => '账号不能为空',
            'password.regex' => '密码格式错误',
            'password.required' => '密码不能为空',
        ];
    }
}
