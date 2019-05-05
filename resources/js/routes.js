import VueRouter from 'vue-router'

let routes = [
    {
        path: '/',
        name: 'home',
        components: require('./components/home/Home')
    },
    {
        path: '/login',
        name: 'login',
        components: require('./components/auth/Login')
    },
];

export default new VueRouter({
    mode: 'history',
    routes
})