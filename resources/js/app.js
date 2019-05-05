require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import router from './routes'
import App from './components/App'
import axios from "axios"
import './components/validate/validate'

Vue.use(VueRouter);

Vue.prototype.$axios = axios;

Vue.component('app', App);

new Vue({
    el: '#app',
    router
});
