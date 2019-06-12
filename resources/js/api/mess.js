import base from './base';
import instance from '../axios/http';

const mess = {

    getUnread () {
        return instance.get(`${base.prefix}/unread`);
    },

    markRead (formData) {
        return instance.post(`${base.prefix}/markread`, formData);
    },

    markAll (formData) {
        return instance.post(`${base.prefix}/markAll`, formData);
    },

    getRead () {
        return instance.get(`${base.prefix}/read`);
    },

    removeRead (formData) {
        return instance.patch(`${base.prefix}/remread`, formData);
    },

    removeAll (formData) {
        return instance.patch(`${base.prefix}/removeAll`, formData);
    },

    getRecyle () {
        return instance.get(`${base.prefix}/recyle`);
    },

    restoreRecyle (formData) {
        return instance.patch(`${base.prefix}/restore`, formData);
    },

    emptyAll (formData) {
        return instance.patch(`${base.prefix}/emptyAll`, formData);
    },

    emptyOne (formData) {
        return instance.patch(`${base.prefix}/emptyOne`, formData);
    }
};

export default mess;

