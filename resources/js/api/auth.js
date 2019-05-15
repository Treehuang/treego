import base from './base';

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
};

export default auth;