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
import JsonExcel from 'vue-json-excel';

// import ElementUI from 'element-ui';
import { Menu, Radio, Row, Col, Card, Tooltip, Submenu, MenuItem, MenuItemGroup, Table, TableColumn, Pagination, Button, Tag, Select, Option, OptionGroup, Input, Dialog, Badge, Upload, Image, DatePicker, TimePicker, Switch } from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

// 全局引入数据验证
import './veevalidate/validate'

// 全局引入geetest
import './geetest/gt'

// Vue.use(ElementUI);
Vue.use(Menu); Vue.use(Submenu); Vue.use(Switch);
Vue.use(MenuItem); Vue.use(MenuItemGroup); Vue.use(Radio);
Vue.use(Table); Vue.use(TableColumn); Vue.use(Tooltip);
Vue.use(Pagination); Vue.use(Upload); Vue.use(TimePicker); Vue.use(DatePicker);
Vue.use(Button); Vue.use(Dialog); Vue.use(Image);
Vue.use(Tag); Vue.use(Input); Vue.use(Badge); Vue.use(Row); Vue.use(Col); Vue.use(Card);
Vue.use(Select); Vue.use(Option); Vue.use(OptionGroup);
Vue.use(VueRouter);
Vue.use(LightTimeline);

Vue.prototype.$api = api; 		// 将api挂载到vue的原型上
Vue.prototype.$axios = axios;
Vue.prototype.$swal = swal;

Vue.component('app', App);
Vue.component('downloadExcel', JsonExcel);

new Vue({
    el: '#app',
    router,
    store,
});
