const state = {
    is_manager: 0,
};

const getters = {

};

const mutations = {
    setManager(state) {
        state.is_manager = 1;
    },

    resetManager(state) {
        state.is_manager = 0;
    }
};

const actions = {
    setManager({commit}) {
        commit('setManager');
    },

    resetManager({commit}) {
        commit('resetManager');
    },
};

export default {
    namespaced: true,    // 在别的文件里面使用 mapGetters、mapActions
    state,
    getters,
    mutations,
    actions
}