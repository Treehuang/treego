<template>
    <ul class="navbar-nav mr-auto">
        <li><router-link tag="a" to="/" :class="[{'active': this.$route.path === '/'}]"><span class="side">首页</span></router-link></li>
        <li><router-link tag="a" to="/ticket" :class="[{'active': this.$route.path === '/ticket'}]"><span class="side">Ticket office</span></router-link></li>
    </ul>
</template>

<script>
    export default {
        name: 'LeftSide',
    }
</script>

<style scoped>

    a {
        font-weight: bold;
        color: #67838c;
        padding: 9px 11px 9px 11px;
        border-radius: 5px;
    }

    a:hover {
        background-color: rgba(76, 148, 160, 0.1);
    }

    .active {
        font-weight: bold;
        color: #4c94a0;
    }

    .side {
        font-size: 16px;
    }
</style>