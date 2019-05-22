<template>
        <ul v-if="!isAuth" class="navbar-nav navbar-right">
            <li class="nav-item"><router-link class="nav-link" to="/login"><i class="fas fa-sign-in-alt"></i> 登录</router-link></li>
            <li class="nav-item"><router-link class="nav-link" to="/register"><i class="fas fa-user-plus"></i> 注册</router-link></li>
        </ul>

        <ul v-else-if="isAuth" class="navbar-nav navbar-right">
            <the-avatar></the-avatar>
        </ul>
</template>

<script>
    import {mapGetters} from 'vuex';
    import TheAvatar from './TheAvatar';

    export default {
        name: 'TheEntry',

        components: {
            TheAvatar,
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

</style>