import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate"

import certification from './modules/certification'

Vue.use(Vuex);

export default new Vuex.Store({

    modules: {
        certification
    },

    plugins: [createPersistedState({
        reducer(val) {
            return {
                certification: val.certification
            }
        }
    })]
})