import base from './base';
import instance from '../axios/http';

const management_audit = {
    // 管理系统学籍审核 -- 待审核（全部）
    getNoAudits() {
        return instance.get(`${base.prefix}/management/noaudits`);
    },

    // 管理系统学籍审核 -- 待审核（7条）
    getNoAudit() {
        return instance.get(`${base.prefix}/management/noaudit`);
    },
    // 管理系统学籍审核 -- 获取特定页待审核
    getCurrentPagesNoAudit(formData) {
        return instance.get(`${base.prefix}/management/pagesnoaudit`, formData);
    },

    // 管理系统学籍审核 -- 管理员通过审核
    throwAudit(formData) {
        return instance.post(`${base.prefix}/management/throwaudit`, formData);
    },

    // 管理系统学籍审核 -- 管理员不给通过审核
    noThrowAudit(formData) {
        return instance.post(`${base.prefix}/management/nothrowaudit`, formData);
    },

    // 管理系统学籍审核 -- 已审核（7条）
    getAudit() {
        return instance.get(`${base.prefix}/management/audit`);
    },

    // 管理系统学籍审核 -- 获取特定页已审核
    getCurrentPagesAudit(formData) {
        return instance.get(`${base.prefix}/management/pagesaudit`, formData);
    },

    // 管理系统学籍审核 -- 获取不能通过审核的原因
    getNoPassReason(formData) {
        return instance.get(`${base.prefix}/management/nopassreason`, formData);
    },

    // 管理系统学籍审核 -- 通过审核（7条）
    getThrowAudit() {
        return instance.get(`${base.prefix}/management/pass`);
    },

    // 管理系统学籍审核 -- 获取特定页通过审核
    getCurrentPagesThrowAudit(formData) {
        return instance.get(`${base.prefix}/management/pagesthrowaudit`, formData);
    },

    // 管理系统学籍审核 -- 未通过审核（7条）
    getNoThrowAudit() {
        return instance.get(`${base.prefix}/management/nopass`);
    },

    // 管理系统学籍审核 -- 获取特定页未通过审核
    getCurrentPagesNoThrowAudit(formData) {
        return instance.get(`${base.prefix}/management/pagesnothrowaudit`, formData);
    },
};

export default management_audit;