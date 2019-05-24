<template>
    <div class="container">

        <side-bar></side-bar>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">账号绑定</h5>

                <hr>

                <div>
                    <i class="far fa-envelope"></i>
                    <div class="email">
                        <p v-if="email === ''" class="info">
                            <span class="match">邮箱</span>
                            <span>您还未绑定，请点击绑定</span>
                        </p>
                        <p v-else class="info">
                            <span>邮箱</span>
                            <span>{{ email }}</span>
                            <span>已经绑定</span>
                        </p>
                        <p class="prompt">可用邮箱加密码登录 tree go，可用邮箱找回密码</p>
                    </div>

                    <button v-if="email === ''" class="btn secondary">绑定</button>
                    <button v-else class="btn secondary">修改</button>
                </div>

                <hr>

                <div>
                    <i class="fas fa-mobile-alt"></i>
                    <div class="phone">
                        <p class="info">
                            <span class="match">手机</span>
                            <span>{{ phone }}</span>
                        </p>
                        <p class="prompt">可用手机号加密码登录 tree go，可通过手机号找回密码</p>
                    </div>
                    <button class="btn secondary">修改</button>
                </div>

                <hr>

                <div>
                    <i class="fas fa-shield-alt"></i>
                    <div class="password">
                        <p class="info">
                            <span class="match">密码</span>
                            <span>已设置</span>
                        </p>
                        <p class="prompt">用于保护账号信息和登录安全</p>
                    </div>
                    <button class="btn secondary">修改</button>
                </div>

                <hr>

                <div>
                    <i class="far fa-comment"></i>
                    <div style="float: left">
                        <p class="info">
                            <span class="match">社交账号</span>
                        </p>
                        <p class="prompt">绑定第三方账号，可以直接登录</p>
                    </div>

                    <div class="clearfix"></div>

                    <i class="fab fa-weixin"></i>
                    <i class="fab fa-weibo"></i>
                    <i class="fab fa-qq"></i>
                    <i class="fab fa-github"></i>

                </div>

            </div>
        </div>

    </div>
</template>

<script>
    import SideBar from './SideBar';

    export default {
        components: {SideBar},

        data() {
            return {
                email: '',
                phone: '156******51',
            }
        }
    }
</script>

<style scoped>
    .container {
        min-width: 1100px;
    }

    .card {
        border: none;
        background-color: #f8fafc
    }

    .card-body {
        width: 800px;
    }

    .fa-envelope {
        float: left; margin-left: 20px; margin-right: 20px; font-size: 27px; color: #c6cace
    }

    .email {
        float: left;
        margin-right: 300px;
    }

    .info {
        margin-bottom: 2px;
    }

    .prompt {
        color: #acb0b4;
    }

    .secondary {
        width: 70px; font-size: 15px; border-radius: 20px; margin-top: 8px; margin-bottom: 8px; color: #6c757d; border-color: #6c757d; box-shadow: none;
    }

    .secondary:hover {
        color: #5ca9b7; border-color: #5da9b6; background-color: rgba(200, 255, 193, 0);
    }

    .fa-mobile-alt {
        float: left; margin-left: 25px; margin-right: 24px; font-size: 27px; color: #c6cace
    }

    .phone {
        float: left; margin-right: 260px;
    }

    .match {
        font-size: 17px;
    }

    .fa-shield-alt {
        float: left; margin-left: 20px; margin-right: 20px; font-size: 27px; color: #c6cace
    }

    .password {
        float: left; margin-right: 419px
    }

    .fa-comment {
        float: left; margin-left: 20px; margin-right: 20px; font-size: 27px; color: #c6cace
    }

    .fa-weixin {
        float: left; margin-left: 65px; margin-right: 80px; font-size: 40px; color: #a0a2a7
    }

    .fa-weibo {
        float: left; margin-right: 80px; font-size: 40px; color: #a0a2a7
    }

    .fa-qq {
        float: left; margin-right: 80px; font-size: 35px; color: #a0a2a7
    }

    .fa-github {
        font-size: 35px; color: #a0a2a7
    }
</style>