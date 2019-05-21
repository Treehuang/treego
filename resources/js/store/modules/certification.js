import api from '../../api'

const state = {
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

    setIsAuth(state) {
        state.is_auth = true;
    },

    setAuthUser(state, data) {
        state.is_auth = true;
        state.access_token = data.access_token;
    },

    refreshToken(state, access_token) {
        state.access_token = access_token;
    },

    resetAuthUser(state) {
        state.is_auth = false;
        state.access_token = null;
    }
};

const actions = {

    // 注册
    register({commit}, formData) {
        return new Promise((resolve, reject) => {
            return api.auth.register(formData).then(response => {
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
            return api.auth.login(formData).then(response => {
                commit('setAuthUser', response.data);
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    logout({commit}) {
        return new Promise((resolve, reject) => {
            return api.auth.logout().then(response => {
                commit('resetAuthUser');
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    tryLogin({commit}) {
        return new Promise((resolve, reject) => {
            return api.auth.tryLogin().then(response => {
                commit('setIsAuth');
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

