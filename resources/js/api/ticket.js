import base from './base';
import instance from '../axios/http';

const ticket = {

    getTicketsOfficeState() {
        return axios.get(`${base.prefix}/ticketsOfficeState`);
    },

    getAuthTickets() {
        return instance.get(`${base.prefix}/authTickets`);
    },

    getNoAuthTickets() {
        return axios.get(`${base.prefix}/noAuthTickets`);
    },

    addToUniversity(formData) {
        return instance.post(`${base.prefix}/toUniversity`, formData);
    },

    addToOsmanthus(formData) {
        return instance.post(`${base.prefix}/toOsmanthus`, formData);
    },

    getBuyTickets() {
        return instance.get(`${base.prefix}/tickets`);
    }
};

export default ticket;