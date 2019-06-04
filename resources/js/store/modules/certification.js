import api from '../../api'

const state = {
    id: null,
    sex: null,
    email: null,
    phone: null,
    avatar: null,
    username: null,
    is_auth: false,
    access_token: null,
};

const getters = {

    isAuth(state) {
        return state.is_auth
    },

    getAccessToken(state) {
        return state.access_token
    },
};

const mutations = {

    setAuthUser(state, data) {
        state.id = data.id;
        state.sex = data.sex;
        state.is_auth = true;
        state.email = data.email;
        state.phone = data.phone;
        state.avatar = data.avatar;
        state.username = data.username;
        state.access_token = data.meta.access_token;
    },

    resetAuthUser(state) {
        state.id = null;
        state.sex = null;
        state.is_auth = false;
        state.email = null;
        state.phone = null;
        state.avatar = null;
        state.username = null;
        state.access_token = null;
    },

    refreshToken(state, access_token) {
        state.access_token = access_token;
    },

    updateUser(state, data) {
        state.sex = data.sex;
        state.email = data.email;
        state.phone = data.phone
        state.avatar = data.avatar;
        state.username = data.username;
    },
};

const actions = {

    // 注册
    register({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.auth.register(formData).then(response => {
                commit('setAuthUser', response.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    // 刷新token
    refreshToken({commit}, access_token) {
        return new Promise(function (resolve, reject) {
            commit('refreshToken', access_token);
        });
    },

    login({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.auth.login(formData).then(response => {
                commit('setAuthUser', response.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    logout({commit}) {
        return new Promise((resolve, reject) => {
            api.auth.logout().then(response => {
                commit('resetAuthUser');
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    tryLogin({commit}) {
        return new Promise((resolve, reject) => {
            api.auth.tryLogin().then(response => {
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    updateUser({commit}, formData) {
        return new Promise((resolve, reject) => {
            api.auth.update(formData).then(response => {
                commit('updateUser', response.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },
};

export default {
    namespaced: true,    // 在别的文件里面使用 mapGetters、mapActions
    state,
    getters,
    mutations,
    actions
}

