/*
 * axios封装
 * 请求拦截、响应拦截、错误统一处理
 */

import axios from 'axios';
import router from '../routes';
import store from '../store/index';

// 创建axios实例
let instance = axios.create({timeout : 1000 * 12});

// 设置post请求头
instance.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';


/**
 * 跳转登录页
 * 携带当前页面路由，以期在登录页面完成登录后返回当前页面
 */
const toLogin = () => {
    router.replace({
        path: '/login',
        query: {
            redirect: router.currentRoute.fullPath
        }
    });
};

/*
 * 请求拦截器
 */
instance.interceptors.request.use(config => {
        config.headers.authorization = store.state.certification.access_token;
        return config;
    },

    error => {
        // 请求等待重发
        return Promise.reject(error);
    }
);

instance.interceptors.response.use(response => {
        let access_token = response.headers.authorization;
        if (access_token) {
            store.dispatch('certification/refreshToken', access_token);
        }

        return response;
    },

    error => {
        switch(error.response.status) {
            case 401:
                toLogin();
                break;

            case 500:
                break;
        }

        return Promise.reject(error);
    }
);

export default instance;