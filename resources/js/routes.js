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
    },
    {
        path: '/user/setbindsns',
        name: 'setbindsns',
        components: require('./components/setting/SetBindsns')
    },
    {
        path: '/user/setprofile',
        name: 'setprofile',
        components: require('./components/setting/SetProfile')
    },
    {
        path: '/user/certificat',
        name: 'certificat',
        components: require('./components/setting/CertiFicat')
    }
];

const router = new VueRouter({
    mode: 'history',
    routes
});

export default router;

