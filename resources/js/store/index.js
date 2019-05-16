import Vue from 'vue'
import Vuex from 'vuex'

import certification from './modules/certification'

Vue.use(Vuex);

export default new Vuex.Store({

    modules: {
        certification
    },
})