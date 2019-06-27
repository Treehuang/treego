import base from './base';
import instance from '../axios/http';

const management_account = {
    // 管理系统账号管理 -- 获取最高管理员账号id
    getTopManagemerId() {
        return instance.get(`${base.prefix}/management/topid`);
    },

    // 管理系统账号管理 -- 获取所有账号
    getUsers() {
        return instance.get(`${base.prefix}/management/accounts`);
    },
    // 管理系统账号管理 -- 获取特定页码的账号
    getCurrentPagesUsers(formData) {
        return instance.get(`${base.prefix}/management/pageaccounts`, formData);
    },
    // 管理系统账号管理 -- 冻结账号
    freezeUser(formData) {
        return instance.post(`${base.prefix}/management/freeze`, formData);
    },
    // 管理系统账号管理 -- 解冻账号
    unfreezeUser(formData) {
        return instance.post(`${base.prefix}/management/unfreeze`, formData);
    },
    // 管理系统账号管理 -- 删除账号
    unsetUser(formData) {
        return instance.delete(`${base.prefix}/management/unset`, formData);
    },
    // 管理系统账号管理 -- 批量删除账号
    batchUnsetUser(formData) {
        return instance.delete(`${base.prefix}/management/batchUnset`, formData);
    },

    // 管理系统账号管理 -- 授权管理员
    authorization(formData) {
        return instance.post(`${base.prefix}/management/authorization`, formData);
    },

    // 管理系统账号管理 -- 回收管理员授权
    unAuthorization(formData) {
        return instance.post(`${base.prefix}/management/unAuthorization`, formData);
    }
};

export default management_account;