import base from './base';
import store from '../store/index';
import instance from '../axios/http';

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

    // 解绑手机时发送验证码
    unbindPhoneSms (formData) {
        return instance.post(`${base.prefix}/user/unbindPhoneSmscode`, formData);
    },

    // 绑定手机时发送验证码
    bindPhoneSms (formData) {
        return instance.post(`${base.prefix}/user/bindPhoneSmscode`, formData);
    },

    // 解绑手机时检测验证码
    checkSmsWithUpdate (formData) {
        return instance.post(`${base.prefix}/user/checkSms`, formData);
    },

    // 绑定邮箱时发送验证码
    bindEmailCode (formData) {
        return instance.post(`${base.prefix}/user/bindEmailCode`, formData);
    },

    // 解绑邮箱时发送验证码
    unbindEmailCode (formData) {
        return instance.post(`${base.prefix}/user/unbindEmailCode`, formData);
    },

    // 解绑邮箱时检测验证码
    checkEmailCode (formData) {
        return instance.post(`${base.prefix}/user/checkEmailCode`, formData);
    },

    // 绑定手机
    bindPhone (formData) {
        return instance.patch(`${base.prefix}/user/bindPhone`, formData);
    },

    // 解绑手机
    unbindPhone () {
        return instance.patch(`${base.prefix}/user/unbindPhone`);
    },

    // 解绑邮箱
    unbindEmail () {
        return instance.patch(`${base.prefix}/user/unbindEmail`);
    },

    // 绑定邮箱
    bindEmail (formData) {
        return instance.patch(`${base.prefix}/user/bindEmail`, formData);
    },

    // 修改密码
    updatePassword (formData) {
        return instance.patch(`${base.prefix}/user/updatePassword`, formData);
    },

    // 登录
    login (formData) {
        return axios.post(`${base.prefix}/login`, formData);
    },

    // 退出登录
    logout () {
        return instance.delete(`${base.prefix}/logout`);
    },

    // 尝试登录
    tryLogin () {
        return instance.post(`${base.prefix}/trylogin`);
    },

    // 获取个人信息
    me () {
        return instance.get(`${base.prefix}/me`);
    },

    // 修改个人信息
    update (formData) {
        return instance.patch(`${base.prefix}/user/profile`, formData);
    },

    // 修改头像（上传头像）
    uploadAvatar (formData) {

        return axios.post(`${base.prefix}/user/avatar`, formData, {

            headers: {
                'Content-Type': 'multipart/form-data',
                'authorization': store.state.certification.access_token,
            }
        });
    },

    // 修改头像（换一换）
    changeAvatar (formData) {
        return instance.patch(`${base.prefix}/user/changeAvatar`, formData);
    },

    test() {
        return instance.get(`${base.prefix}/me`);
    }
};

export default auth;