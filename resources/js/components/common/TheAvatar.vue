<template>
    <li class="nav-item dropdown">

        <router-link tag="a" to="" @click.native="isDown=false" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown">
            <img :src="avatar" @mouseover="isDown=true" class="rounded-circle rounded-circle-puls" width="30px" height="30px"/>
        </router-link>

        <div v-if="isDown" class="dropdown-menu dropdown-menu-right">
            <div>
                <router-link tag="a" to="">
                    <img :src="avatar" class="rounded-circle rounded-img" width="70px" height="70px"/>
                </router-link>
            </div>

            <div style="margin-top: 10px">
                <h6>{{ username }}</h6>
                <h6 class="prompt">经验 1050 积分 80</h6>
            </div>

            <div class="clearfix"></div>

            <div style="margin-bottom: 1px">
                <router-link class="btn center" tag="a" to="" @click.native="test"><i class="far fa-credit-card"></i>&nbsp;<span>卡券中心</span></router-link>
                <router-link class="btn set" tag="a" to="/user/setbindsns" @click.native="isDown=false"><i class="fas fa-cog"></i>&nbsp;<span>个人设置</span></router-link>
            </div>

            <div style="margin-bottom: 10px">
                <router-link class="btn shop" tag="a" to="" @click.native="isDown=false"><i class="fas fa-shopping-cart"></i>&nbsp;<span>积分商城</span></router-link>
                <router-link class="btn bell" tag="a" to="/unread" @click.native="isDown=false"><i class="far fa-bell"></i>&nbsp;<span>消息管理</span></router-link>
            </div>

            <div class="prompt">
                <i class="far fa-clock"></i>
                <span>上次登录的时间: 2019-5-20</span>
            </div>

            <hr>

            <router-link tag="a" to="" @click.native="logoutSure">
                <span class="logout">安全退出</span>
            </router-link>
        </div>

        <!-- 退出提示-->
        <el-dialog title="提示" :visible.sync="logoutVisible" width="300px" :show-close="showClose" center>
            <div class="del-dialog-cnt">您确定退出登录？</div>
            <span slot="footer" class="dialog-footer">
                <span class="cancel" @click="logoutVisible = false">取 消</span>
                <span class="sure" @click="logout">确 定</span>
            </span>
        </el-dialog>
    </li>

</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'TheAvatar',

        computed: {
            ...mapState({
                token : state => state.certification.token,
                avatar: state => state.certification.avatar,
                username: state => state.certification.username,
            })
        },

        created() {
            if(this.token !== '') {
                this.$store.dispatch('certification/tryLogin').then(() => {});
            }
        },

        data() {
            return {
                isDown: true,
                showClose: false,
                logoutVisible: false,
            }
        },

        methods: {
            logout() {
                this.logoutVisible = false;
                this.$store.dispatch('certification/logout').then(() => {
                    this.$router.push({name: 'home'});
                });
            },

            logoutSure() {
                this.isDown = false;
                this.logoutVisible = true;
            }
        }
    }
</script>

<style scoped>
    .dropdown-toggle {
        width: 46px;
        cursor:pointer;
        padding: 4px;
    }

    .dropdown-menu-right {
        background-color: #f4f6f8;
        width: 253px;
        padding: 30px 20px 10px 20px;
        border: 0;
        border-radius: 0;
        margin-top: 0;
        box-shadow: 2px 2px 30px -2px rgba(96, 96, 96, 0.2);
    }

    .fa-cog, .fa-shopping-cart, .fa-credit-card {
        color: rgba(13,13,13,0.62);
    }

    .fa-bell {
        margin-left: 1px;
        margin-right: 1px;
        font-size: 15px;
        color: rgba(13,13,13,0.62);
    }

    span {
        font-size: 12px;
    }

    /*悬停下拉菜单延迟*/
    li:hover .dropdown-menu {display: block; animation:ttt 1s 1 forwards;}

    .li .dropdown-menu {
        opacity:0
    }

    @keyframes ttt {
        0% {
            opacity: 0;
        }
        1% { /*这里表示3s的66%也就是大约2秒的时候*/
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
    }

    .rounded-circle-puls {
        margin-right: 30px;
        border: solid #a6b7bf 2px
    }

    .rounded-circle-puls:hover {
        border: solid #c1d2d7 2px;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .rounded-img {
        float: left;
        margin-right: 14px;
        margin-bottom: 20px;
    }

    .btn {
        box-shadow: none;
        width: 106px;
        border-radius: 0;
        border-color: #ecf0f3;
        background-color: #ecf0f3;
    }

    .btn:hover {
        border-color: #d5dde2;
        background-color: #d5dde2;
    }

    .shop,
    .center
    {
        float: left;
        margin-right: 1px;
    }

    hr {
        margin-top: 8px;
        margin-bottom: 12px;
        border-top: 1px solid rgb(222, 233, 234);
    }

    .prompt {
        color: #93999f;
    }

    .logout {
        color: #45555d;
    }

    .logout:hover {
        color: #54a3af;
    }

    .sure {
        cursor: pointer;
        color: #ffffff;
        padding: 7px 14px;
        background: #4d94a0;
        border: 1px solid #4d94a0 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-left: 8px;
    }

    .sure:hover {
        background:#50a3af;
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