<?php

namespace App\Http\Requests\Api;

use Illuminate\Http\Request;
use Dingo\Api\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\ValidationHttpException;

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
            'geetest_challenge' => 'required|geetest',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->container['request'] instanceof Request) {
            throw new ValidationHttpException($validator->errors(), null, [], $code = 250);
        }

        parent::failedValidation($validator);
    }

    public function messages()
    {
        return [
            'geetest' => '验证码校验失败'
        ];
    }
}
