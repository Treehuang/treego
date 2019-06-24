<template>
        <ul v-if="!isAuth" class="navbar-nav navbar-right">
            <li class="nav-item">
                <router-link tag="a" class="nav-link" to="/login" style="padding-right: 0">
                    <i class="fas fa-sign-in-alt login"> 登录</i>
                </router-link>
            </li>

            <li class="nav-item">
                <router-link tag="a" class="nav-link" to="/register">
                    <i class="fas fa-user-plus register"><span style="font-size: 14px"> 注册</span></i>
                </router-link>
            </li>
        </ul>

        <ul v-else-if="isAuth" class="navbar-nav navbar-right">

            <the-management></the-management>

            <my-ticket></my-ticket>

            <the-bell></the-bell>

            <the-avatar></the-avatar>

        </ul>
</template>

<script>
    import {mapGetters} from 'vuex';
    import TheAvatar from './TheAvatar';
    import TheBell from './TheBell';
    import MyTicket from './MyTicket';
    import TheManagement from '../managment/common/TheManagement';

    export default {
        name: 'TheEntry',

        components: {
            TheManagement,
            TheAvatar,
            TheBell,
            MyTicket,
        },

        computed: {
            ...mapGetters('certification', {
                isAuth: 'isAuth'
            })
        },
    }
</script>

<style scoped>

    .fa-user-plus {
        font-size: 12px;
    }

    .fa-sign-in-alt {
        font-size: 14px;
    }

    .login, .register {
        color: #67838c;
        padding: 5px 10px 5px 10px;
        border-radius: 4px;
        border: 1px solid #dcdfe6;
        font-weight: bold;
    }

    .login:hover, .register:hover {
        cursor: pointer;
        color: #4c94a0;
        border: 1px solid rgba(76, 148, 160, 0.15);
        background-color: rgba(76, 148, 160, 0.15);
    }
</style>