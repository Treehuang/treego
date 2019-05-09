require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import router from './routes'
import App from './components/App'
import axios from "axios"

// 全局引入数据验证
import './veevalidate/validate'

// 全局引入geetest
import './geetest/gt'

Vue.use(VueRouter);

Vue.prototype.$axios = axios;

Vue.component('app', App);

new Vue({
    el: '#app',
    router
});
