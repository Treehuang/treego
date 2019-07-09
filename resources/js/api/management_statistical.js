import base from './base';
import instance from '../axios/http';

const magagement_statistical = {
    // 管理系统统计中心 -- 获取激活的车票
    getActiveFare() {
        return instance.get(`${base.prefix}/management/activefares`);
    },

    // 管理系统统计中心 -- 获取每个起始点车票的订购情况
    getCountFares() {
        return instance.get(`${base.prefix}/management/countfares`);
    },

    // 管理系统统计中心 -- 获取所有用户订购的车票(7条)
    getAllBuyFares() {
        return instance.get(`${base.prefix}/management/buyfares`);
    },

    // 管理系统统计中心 -- 获取所有用户订购的车票(特定页)
    getCurrentBuyFares(formData) {
        return instance.get(`${base.prefix}/management/currentbuyfares`, formData);
    },

    // 管理系统统计中心 -- 搜索
    getSearchFares(formData) {
        return instance.get(`${base.prefix}/management/searchfares`, formData);
    },

    // 管理系统统计中心 -- 搜索(特定页)
    getCurrentSearchFares(formData) {
        return instance.get(`${base.prefix}/management/currentsearchfares`, formData);
    },

    // 管理系统统计中心 -- execl数据（全部）
    getExeclFares() {
        return instance.get(`${base.prefix}/management/execlfares`);
    },

    // 管理系统统计中心 -- execl数据（特定）
    getExeclFare(formData) {
        return instance.get(`${base.prefix}/management/execlfare`, formData);
    },

    // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（7条）
    getUnOsFare(formData) {
        return instance.get(`${base.prefix}/management/unosfare`, formData);
    },

    // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（特定）
    getCurrentUnOsFare(formData) {
        return instance.get(`${base.prefix}/management/currentunosfare`, formData);
    },

    // 管理系统统计中心 -- 抵达大学城或桂花岗的车票（全部）
    getUnOsFares(formData) {
        return instance.get(`${base.prefix}/management/unosfares`, formData);
    },
};

export default magagement_statistical;