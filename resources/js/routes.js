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
    {
        path: '/register',
        name: 'register',
        components: require('./components/auth/Register')
    },
    {
        path: '/verify',
        name: 'verify',
        components: require('./components/auth/Verify')
    }
];

export default new VueRouter({
    mode: 'history',
    routes
})