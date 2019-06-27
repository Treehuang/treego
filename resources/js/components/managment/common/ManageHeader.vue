<template>
        <nav :class="[{'content-collapse': collapse}, 'navbar', 'navbar-static-top']">
            <div class="collapse-btn" @click="collapseChage">
                <i class="el-icon-menu"></i>
            </div>

            <ul class="navbar-nav mr-auto">
                <li class="welcome">欢迎来到 tree go 管理后台</li>
            </ul>

            <ul class="navbar-nav navbar-right">
                <li class="nav-item" @click="exit">
                    <router-link tag="a" to="/">
                        <i class="fas fa-undo-alt"> 返回</i>
                    </router-link>
                </li>
            </ul>
        </nav>
</template>

<script>

    import { mapState } from 'vuex';
    import bus from '../../../bus/bus';

    export default {
        name: 'ManageHeader',

        inject: ['exitManagement'],

        created() {
            this.$swal.fire({
                text: '欢迎您，tree go管理员',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500
            })
        },

        computed: {
            ...mapState({
                username : state => state.certification.username
            })
        },

        data() {
            return {
                collapse: true,
            }
        },

        methods: {
            // 侧边栏折叠
            collapseChage(){
                this.collapse = !this.collapse;
                bus.$emit('collapse', this.collapse);
            },

            // 退出后台
            exit() {
                // 标志退出后台管理系统
                this.$store.commit('management/resetManager');
                this.exitManagement();
                // 显示首页
                this.$router.push('/');
            }
        },

    }
</script>

<style scoped>
    .navbar-static-top {
        background-color: #1ab394;
        margin-top: 0;
        margin-bottom: 0;
        padding: 13px 80px 13px 20px;
        left: 205px;
        right: 0;
        width: auto;
        transition: left .3s ease-in-out;
        position: absolute;
    }

    .welcome {
        color: #ffffff;
        margin-left: 15px;
    }

    .el-icon-menu {
        color: #ffffff;
        font-size: 27px;
    }

    .el-icon-menu:hover, a:hover {
        cursor: pointer;
        color: #ebebeb;
    }

    a {
        color: #ffffff;
    }

    .content-collapse {
        left: 64px !important;
    }

</style>