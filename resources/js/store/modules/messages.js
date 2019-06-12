import api from '../../api'

const state = {
    readNum: 0,
    unReadNum: 0,
    recycleNum: 0,
};

const getters = {

};

const mutations = {

    setReadNum(state, data) {
        state.readNum = data;
    },

    increaseReadNum(state, data) {
        state.readNum = state.readNum + data;
    },

    reduceReadNum(state, data) {
        state.readNum = state.readNum - data;
    },

    setUnreadNum(state, data) {
        state.unReadNum = data;
    },

    reduceUnreadNum(state, data) {
        state.unReadNum = state.unReadNum - data;
    },

    setRecycleNum(state, data) {
        state.recycleNum = data;
    },

    increaseRecycleNum(state, data) {
        state.recycleNum = state.recycleNum + data;
    },

    reduceRecycleNum(state, data) {
        state.recycleNum = state.recycleNum - data;
    }
};

const actions = {

    getUnread({commit}) {
        return new Promise((resolve, reject) => {
            api.mess.getUnread().then(response => {
                commit('setUnreadNum', response.data.unReadList.length);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    markRead({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.mess.markRead(formData).then(response => {
                commit('increaseReadNum', 1);
                commit('reduceUnreadNum', 1);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    markAll({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.mess.markAll(formData).then(response => {
                commit('setUnreadNum', 0);
                commit('increaseReadNum', formData.messList.length);
                resolve(response);
            }).catch(error => {
               reject(error);
            });
        });
    },

    getRead({commit}) {
        return new Promise((resolve, reject) => {
            api.mess.getRead().then(response => {
                commit('setReadNum', response.data.readList.length);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    removeRead({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.mess.removeRead(formData).then(response => {
                commit('reduceReadNum', 1);
                commit('increaseRecycleNum', 1);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        })
    },

    removeAll({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.mess.removeAll(formData).then(response => {
                commit('setReadNum', 0);
                commit('increaseRecycleNum', formData.messageIdList.length)
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        })
    },

    getRecyle({commit}) {
        return new Promise((resolve, reject) => {
            api.mess.getRecyle().then(response => {
                commit('setRecycleNum', response.data.recyleList.length);
                resolve(response);
            }).catch(error => {
                reject(error);
            })
        });
    },

    restoreRecyle({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.mess.restoreRecyle(formData).then(response => {
                commit('reduceRecycleNum', 1);
                commit('increaseReadNum', 1);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    emptyAll({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.mess.emptyAll(formData).then(response => {
                commit('setRecycleNum', 0);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    emptyOne({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.mess.emptyOne(formData).then(response => {
                commit('reduceRecycleNum', 1);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    }
};

export default {
    namespaced: true,    // 在别的文件里面使用 mapGetters、mapActions
    state,
    getters,
    mutations,
    actions
}