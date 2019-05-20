<template>
        <ul v-if="!isAuth" class="navbar-nav navbar-right">
            <li class="nav-item"><router-link class="nav-link" to="/login"><i class="fas fa-sign-in-alt"></i> 登录</router-link></li>
            <li class="nav-item"><router-link class="nav-link" to="/register"><i class="fas fa-user-plus"></i> 注册</router-link></li>
        </ul>

        <ul v-else-if="isAuth" class="navbar-nav navbar-right">
            <li class="nav-item dropdown">
                <router-link tag="a" to="" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="https://img.mukewang.com/5bac8e1e0001705a06400640-200-200.jpg" class="rounded-circle" width="30px" height="30px">
                </router-link>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <router-link class="dropdown-item" tag="a" to=""><i class="fas fa-user"></i>&nbsp;&nbsp;&nbsp;<span>个人中心</span></router-link>
                    <router-link class="dropdown-item" tag="a" to="" @click.native="test"><i class="fas fa-user-cog"></i>&nbsp;&nbsp;&nbsp;<span>个人设置</span></router-link>
                    <router-link class="dropdown-item" tag="a" to="" @click.native="logout">
                        <i class="fas fa-paper-plane"></i>&nbsp;&nbsp;&nbsp;<span>安全退出</span>
                    </router-link>
                </div>
            </li>
        </ul>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: 'TheEntry',

        computed: {
            ...mapGetters('certification', {
                isAuth: 'isAuth'
            })
        },

        methods: {
            logout() {
                this.$store.dispatch('certification/logout').then(response => {
                    this.$router.push({name:'home'});
                }).catch(error => {

                });
            },

            test() {
                this.$api.auth.test().then(response => {
                    console.log(response.data);
                }).catch(error => {

                });
            }
        }
    }
</script>

<style scoped>

    .fa-user-plus {
        font-size: 12px;
    }

    .fa-sign-in-alt {
        font-size: 14px;
    }

    .dropdown-toggle {
        width: 93px;
        cursor:pointer;
    }

    .dropdown-menu-right {
        padding: 0;
        min-width:100%;
        margin-top: 0.4px;
        text-align: center;
    }

    .fa-user {
        margin-right: 4px;
        color: rgba(13,13,13,0.62);
    }

    .fa-user-cog {
        color: rgba(13,13,13,0.62);
    }

    .fa-paper-plane {
        margin-right: 5px;
        color: rgba(13,13,13,0.62);
    }

    span {
        font-size: 15px;
    }

    /*悬停下拉菜单延迟*/
    li:hover .dropdown-menu {display: block; animation:ttt 1s 1 forwards;}

    .li .dropdown-menu{
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
</style>