import VueRouter from 'vue-router'

let routes = [
    {
        path: '/',
        components: require('./components/home/Home')
    }
];

export default new VueRouter({
    mode: 'history',
    routes
})