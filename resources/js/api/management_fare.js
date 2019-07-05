import base from './base';
import instance from '../axios/http';

const management_fare = {
    // 管理系统车票系统 -- 添加车票
    addFares(formData) {
        return instance.post(`${base.prefix}/management/addfares`, formData);
    },

    // 管理系统车票系统 -- 管理车票--获取车票
    getAllFares() {
        return instance.get(`${base.prefix}/management/allfares`);
    },

    // 管理系统车票系统 -- 管理车票--获取特定页车票
    getCurrentFares(formData) {
        return instance.get(`${base.prefix}/management/currentfares`, formData);
    },

    // 管理系统车票系统 -- 管理车票--删除车票
    deleteFare(formData) {
        return instance.delete(`${base.prefix}/management/unsetfare`, formData);
    },

    // 管理系统车票系统 -- 管理车票--激活车票
    activeFare(formData) {
        return instance.patch(`${base.prefix}/management/activefare`, formData);
    },

    // 管理系统车票系统 -- 管理车票--车票失效
    unActiveFare(formData) {
        return instance.patch(`${base.prefix}/management/unactivefare`, formData);
    },

    // 管理系统车票系统 -- 管理车票--开启/关闭Ticket Office
    setTicketOffice(formData) {
        return instance.patch(`${base.prefix}/management/setTicketOffice`, formData);
    },
};

export default management_fare;