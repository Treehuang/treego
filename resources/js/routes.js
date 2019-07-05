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
    },
    {
        path: '/read',
        name: 'read',
        components: require('./components/message/Read')
    },
    {
        path: '/unread',
        name: 'unread',
        components: require('./components/message/UnRead')
    },
    {
        path: '/recyle',
        name: 'recyle',
        components: require('./components/message/Recycle')
    },
    {
        path: '/ticketOffice',
        name: 'ticketOffice',
        components: require('./components/ticket/TicketOffice')
    },
    {
        path: '/ticketList',
        name: 'ticketList',
        components: require('./components/ticket/TicketList')
    },
    {
        path: '/mhome',
        name: 'mHome',
        components: require('./components/managment/mHome'),
        meta: { title: '系统首页' }
    },
    {
        path: '/maccount',
        name: 'mAccount',
        components: require('./components/managment/maccount/mAccount'),
        meta: { title: '账号管理' }
    },
    {
        path: '/certificatCheck',
        name: 'certificatCheck',
        components: require('./components/managment/certificatCheck/certificatCheck'),
        meta: { title: '学籍审核' }
    },
    {
        path: '/setTickets',
        name: 'setTickets',
        components: require('./components/managment/ticketCenter/SetTicket'),
        meta: { title: '添加车票' }
    },
    {
        path: '/opeTickets',
        name: 'opeTickets',
        components: require('./components/managment/ticketCenter/OperateTicket'),
        meta: { title: '管理车票' }
    }
];

const router = new VueRouter({
    mode: 'history',
    routes
});

export default router;

