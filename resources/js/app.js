require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router'
import router from './routes'
import App from './components/App'
import axios from "axios"
import api from './api';
import store from './store';		// 引入store
import swal from 'sweetalert2';     // 引入sweetalert2
import LightTimeline from 'vue-light-timeline';     // 引入vue-light-timeline

// 全局引入数据验证
import './veevalidate/validate'

// 全局引入geetest
import './geetest/gt'

Vue.use(VueRouter);
Vue.use(LightTimeline);

Vue.prototype.$api = api; 		// 将api挂载到vue的原型上
Vue.prototype.$axios = axios;
Vue.prototype.$swal = swal;

Vue.component('app', App);

new Vue({
    el: '#app',
    router,
    store,
});
