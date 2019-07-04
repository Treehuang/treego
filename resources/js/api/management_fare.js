import base from './base';
import instance from '../axios/http';

const management_fare = {
    // 管理系统车票系统 -- 添加车票
    addFares(formData) {
        return instance.post(`${base.prefix}/management/addfares`, formData);
    }
};

export default management_fare;