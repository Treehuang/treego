import api from '../../api'

const state = {
    is_auth: false,
    expires_in: null,
    access_token: null,
};

const getters = {

    isAuth(state) {
        return state.is_auth
    },

    getAccessToken(state) {
        return state.access_token
    },

    getExpiresTime(state) {
        return state.expires_in
    }
};

const mutations = {

    setAuthUser(state, data) {
        state.is_auth = true;
        //state.expires_in = data.expires_in;
        //state.access_token = data.access_token;
    },

    refreshToken(state, data) {
        state.expires_in = data.expires_in;
        state.access_token = data.access_token;
    }
};

const actions = {

    // 注册
    register({commit}, formData) {
        return new Promise((resolve, reject) => {
            return api.auth.register(formData).then(response => {
                commit('setAuthUser');
                resolve(response);
            }).catch(error => {
                reject(error);
            });
        });
    },

    // 刷新token
    refreshToken({commit}) {
        return new Promise((resolve, reject) => {
            this.$api.auth.refreshToken().then(response => {
                commit('refreshToken', response.data);
                resolve();
            });
        })
    },

    setAuthUser({commit}) {
        commit('setAuthUser');
    }
};

export default {
    namespaced: true,    // 在别的文件里面使用 mapGetters、mapActions
    state,
    getters,
    mutations,
    actions
}

