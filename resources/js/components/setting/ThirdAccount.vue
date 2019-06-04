<template>
    <div>
        <i class="far fa-comment"></i>
        <div style="float: left">
            <p class="info">
                <span class="match">社交账号 (此功能未开放)</span>
            </p>
            <p class="prompt">绑定第三方账号，可以直接登录</p>
        </div>

        <div class="clearfix"></div>

        <i class="fab fa-weixin"></i>
        <i class="fab fa-weibo"></i>
        <i class="fab fa-qq"></i>
        <i class="fab fa-github"></i>

    </div>
</template>

<script>
    export default {
        name: 'ThirdAccount',
    }
</script>

<style scoped>
    .info {
        margin-bottom: 2px;
    }

    .prompt {
        color: #acb0b4;
    }

    .match {
        font-size: 17px;
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