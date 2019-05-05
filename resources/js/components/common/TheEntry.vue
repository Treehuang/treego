<template>
        <ul class="navbar-nav navbar-right">
            <li class="nav-item"><router-link class="nav-link" to="/login"><i class="fas fa-sign-in-alt"></i> 登录</router-link></li>
            <li class="nav-item"><router-link class="nav-link" to="#"><i class="fas fa-user-plus"></i> 注册</router-link></li>
        </ul>
</template>

<script>
    export default {
        name: 'TheEntry'
    }
</script>

<style scoped>

    .fa-user-plus {
        font-size: 12px;
    }

    .fa-sign-in-alt {
        font-size: 14px;
        margin-right: 1px;
    }
</style>