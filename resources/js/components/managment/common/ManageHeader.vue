<template>

        <nav :class="[{'content-collapse': collapse}, 'navbar', 'navbar-static-top', 'navbar-expand-lg']">
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

                <li class="nav-item">
                    <i class="fas fa-power-off" @click="logoutVisible = true"></i>
                </li>

                <el-dialog title="提示" :visible.sync="logoutVisible" width="300px" :show-close="showClose" center>
                    <div class="del-dialog-cnt">您确定退出系统？</div>
                    <span slot="footer" class="dialog-footer">
                        <span class="cancel" @click="logoutVisible = false">取 消</span>
                        <span class="sure" @click="logout">确 定</span>
                    </span>
                </el-dialog>
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
                showClose: false,
                logoutVisible: false,
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
                this.$router.push({name: 'home'});
            },

            // 退出登录
            logout() {
                this.logoutVisible = false;

                this.$store.dispatch('certification/logout').then(() => {
                    // 标志退出后台管理系统
                    this.$store.commit('management/resetManager');
                    this.exitManagement();
                    this.$router.push({name: 'home'});
                });
            }
        },

    }
</script>

<style scoped>
    .navbar-static-top {
        background-color: #1ab394;
        margin-top: 0;
        margin-bottom: 0;
        padding: 13px 40px 13px 20px;
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

    .fa-power-off {
        color: #ffffff;
        font-size: 16px;
        margin-left: 40px;
        margin-top: 3px;
    }

    .el-icon-menu:hover, .fa-power-off:hover, a:hover {
        cursor: pointer;
        color: #ebebeb;
    }

    a {
        color: #ffffff;
    }

    .content-collapse {
        left: 64px !important;
    }

    .sure {
        cursor: pointer;
        color: #ffffff;
        padding: 7px 14px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-left: 8px;
    }

    .sure:hover {
        background: #1ad2b2;
    }

    .cancel {
        cursor: pointer;
        color: #ffffff;
        padding: 7px 14px;
        background: #b4b4b4;
        border: 1px solid #b4b4b4 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-right: 8px;
        margin-left: 4px;
    }

    .cancel:hover {
        background: #aaaaaa;
    }

    .del-dialog-cnt{
        font-size: 16px;
        text-align: center
    }
</style>