import Vue from 'vue';
import VeeValidate, { Validator } from 'vee-validate';

Vue.use(VeeValidate, {events: 'change|blur|input'});

Validator.extend('account', {
    getMessage: field => '请输入正确的手机号或邮箱',
    validate: value => {
        return /^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/.test(value) ||
            /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)
    }
});

Validator.extend('password', {
    getMessage: field => '请输入6-16位密码,区分大小写',
    validate: value => {
        return /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/.test(value)
    }
});

Validator.extend('phone', {
    getMessage: field => '请输入正确的手机号',
    validate: value => {
        return /^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/.test(value)
    }
});

Validator.extend('username', {
    getMessage: field => '用户名格式错误',
    validate: value => {
        return /^[1][0-9]{1}-[\u4e00-\u9fa5]{2,4}-[\u4e00-\u9fa5]{2,4}$/.test(value)
    }
});

Validator.extend('smscode', {
    getMessage: field => '请输入正确的短信验证码',
    validate: value => {
        return /^[0-9]{6}$/.test(value)
    }
})

const dictionary = {
    en: {
        custom : {
            account : {
                required: () => '请输入正确的手机号或邮箱'
            },
            password : {
                required: () => '请输入6-16位密码,区分大小写'
            },
            phone: {
                required: () => '手机号不能为空'
            },
            username : {
                required: () => '用户名不能为空'
            },
            smscode : {
                required: () => '短信验证码不能为空'
            }
        }
    },
    cn: {
        messages: {
            required: () => ''
        }
    }
};

// 自定义validate error 信息
Validator.localize(dictionary);