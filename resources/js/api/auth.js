import base from './base';
import instance from '../axios/http'

const auth = {
    signup (formData) {
        return instance.post(`${base.prefix}/signup`, formData);
    },

    // 注册
    register (formData) {
        return instance.post(`${base.prefix}/register`, formData);
    },

    // 发送验证码
    smscode (formData) {
        return instance.post(`${base.prefix}/smscode`, formData);
    },
};

export default auth;