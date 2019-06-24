import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate"

import certification from './modules/certification'
import messages from './modules/messages'
import management from './modules/management'

Vue.use(Vuex);

export default new Vuex.Store({

    modules: {
        certification,
        messages,
        management,
    },

    plugins: [createPersistedState({
        reducer(val) {
            return {
                certification: val.certification,
                messages: val.messages,
                management: val.management,
            }
        }
    })]
})