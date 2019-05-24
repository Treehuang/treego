<template>
    <div class="sidebar">
        <div class="card-body">

            <div class="avator">
                <img src="https://img.mukewang.com/5bac8e1e0001705a06400640-200-200.jpg" class="rounded-circle" width="100px" height="100px">
                <div class="update">
                    <span style="color: #ffffff; font-size: 12px;">更 换 头 像</span>
                </div>
            </div>

            <div class="username">
                <span style="color: #aaacb1">{{ username }}</span>
                <i v-if="sex === 'm'" class="fas fa-mars"></i>
                <i v-if="sex === 'f'" class="fas fa-venus"></i>
                <i v-if="sex === 's'" class="fas fa-transgender"></i>
            </div>

            <div class="icon">
                <i class="far fa-comment"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fas fa-shield-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="fas fa-mobile-alt"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <i class="far fa-envelope"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </div>

            <div>
                <div class="account">
                    账户管理
                </div>

                <div class="clearfix"></div>

                <hr>

                <div class="list-group">
                    <router-link tag="a" to="/user/setbindsns" :class="[{'active': this.$route.path === '/user/setbindsns'}, 'list-group-item']">绑定账号<i class="fas fa-angle-right"></i></router-link>
                    <router-link tag="a" to="/user/setprofile" :class="[{'active': this.$route.path === '/user/setprofile'}, 'list-group-item']">个人信息<i class="fas fa-angle-right"></i></router-link>
                    <router-link tag="a" to="/user/certificat" :class="[{'active': this.$route.path === '/user/certificat'}, 'list-group-item']">学籍认证<i class="fas fa-angle-right"></i></router-link>
                    <router-link tag="a" to="/user/operatelog" :class="[{'active': this.$route.path === '/user/operatelog'}, 'list-group-item']">操作记录<i class="fas fa-angle-right"></i></router-link>
                </div>

            </div>

        </div>
    </div>

</template>

<script>
    export default {
        name: 'SideBar',

        data() {
            return {
                sex: 'm',
                username: '14-电气-黄树斌',
            }
        }
    }
</script>

<style scoped>

    .sidebar {
        float: left;
        padding:0;
        width:216px;
        min-width: 216px;
        margin-right: 50px;
        margin-bottom: 80px;
        background-color: #f2f4f6;
    }

    .card-body {
        text-align: center;
        position: relative;
        padding: 0;
    }

    .rounded-circle {
        margin-top: 20px;
        border: solid #d9dde1 4px;
    }

    .rounded-circle:hover+.update {
        display: block;
    }

    .avator {
        cursor: pointer;
        text-align: center;
    }

    .update:hover {
        display: block;
    }

    .update {
        border-radius: 0 0 50px 50px;
        width: 86px;
        height: 22px;
        background-color: rgba(0,0,0,.5);
        position: absolute;
        top: 83px;
        left: 65px;
        cursor: pointer;
        display: none;
    }

    .username {
        margin-top: 12px;
        margin-bottom: 13px;
    }

    .icon {
        margin-left: 22px;
        margin-bottom: 15px;
        color: #4c94a0
    }

    .account {
        float: left;
        font-size: 16px;
        color: #aaacb1;
        margin-left: 20px
    }

    hr {
        margin-right: 20px;
        margin-left: 20px;
    }

    a {
        color: #707578;
        background-color: #f2f4f6;
    }

    a:hover {
        color: #ffffff;
        background-color: #3490dc;
    }

    .list-group-item {
        border: 0.1px solid #f2f4f6;
        border-radius: 0;
        text-decoration: none;
    }

    .fa-angle-right {
        float: right;
        margin-top: 4px;
    }

    .fa-venus {
        color: #f06595
    }

    .fa-mars, .fa-transgender {
        color: #4c94a0
    }
</style>