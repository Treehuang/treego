import base from './base';
import instance from '../axios/http'

const auth = {
    signup (formData) {
        return axios.post(`${base.prefix}/signup`, formData);
    },

    // 注册
    register (formData) {
        return axios.post(`${base.prefix}/register`, formData);
    },

    // 发送验证码
    smscode (formData) {
        return axios.post(`${base.prefix}/smscode`, formData);
    },

    // 登录
    login (formData) {
        return axios.post(`${base.prefix}/login`, formData);
    },

    // 退出登录
    logout () {
        return instance.delete(`${base.prefix}/logout`);
    },

    test() {
        return instance.get(`${base.prefix}/me`);
    }
};

export default auth;