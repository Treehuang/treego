<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class GeetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'geetest_challenge' => 'geetest',
        ];
    }

    public function messages()
    {
        return [
            'geetest' => '验证码校验失败'
        ];
    }
}
