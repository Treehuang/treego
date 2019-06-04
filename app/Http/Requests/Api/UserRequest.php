<?php

namespace App\Http\Requests\Api;

use Auth;
use Illuminate\Http\Request;

class UserRequest extends FormRequest
{

    public function rules(Request $request)
    {
        switch (static::getPathInfo()) {
            case '/api/user/profile' :
                if ($request->college == null) {
                    $college = [
                        'bail',
                    ];
                }else {
                    $college = [
                        'bail',
                        'regex: /^[\x{4e00}-\x{9fa5}]{3,12}$/u'
                    ];
                }

                if ($request->introduction == null) {
                    $introduction = [
                        'bail',
                    ];
                }else {
                    $introduction = [
                        'bail',
                        'regex: /^[a-zA-Z0-9\,!，！\.。\x{4e00}-\x{9fa5}]{0,50}$/u'
                    ];
                }

                return [
                    'sex' => [
                        'bail',
                        'required',
                        'regex: /^[m,f,s]{1}$/'
                    ],

                    'campus' => [
                        'bail',
                        'required',
                        'regex: /^[b,g]{1}$/'
                    ],

                    'nickname' => [
                        'bail',
                        'required',
                        'unique:users,username,' . Auth::guard('api')->id(),
                        'regex: /^[1][0-9]{1}-[\x{4e00}-\x{9fa5}]{2,4}-[\x{4e00}-\x{9fa5}]{2,4}$/u',
                    ],

                    'college' => $college,

                    'introduction' => $introduction,
                ];
                break;

            case '/api/user/bindPhone' :
            case '/api/user/bindPhoneSmscode' :
            case '/api/user/unbindPhoneSmscode' :
                return [
                    'phone' => [
                        'bail',
                        'required',
                        'regex: /^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/',
                    ]
                ];

                break;

            default :
                return [];
        }
    }

    public function messages()
    {
        return [
            'sex.required' => '性别不能为空',
            'sex.regex' => '性别格式错误',
            'campus.required' => '校区不能为空',
            'campus.regex' => '校区格式错误',
            'college.regex' => '学院格式错误',
            'nickname.required' => '昵称不能为空',
            'nickname.regex' => '昵称须为级数-专业-姓名',
            'nickname.unique' => '昵称已经存在',
            'introduction.regex' => '签名含有非法字符'
        ];
    }
}
