<template>
    <div>
        <div id="geetest"></div>
        <!-- loading -->
        <div id="wait" class="show sk-three-bounce">
            <div class="sk-bounce-1 sk-child"></div>
            <div class="sk-bounce-2 sk-child"></div>
            <div class="sk-bounce-3 sk-child"></div>
        </div>
    </div>

</template>

<script>

    export default {
        name: 'GeetTest',

        data() {
            return {
                geetestObj: {},
            }
        },

        created() {
            this.getGeetest();
        },

        methods: {
            getGeetest() {
                this.$axios.get('/api/auth/geetest').then(response => {

                    initGeetest({
                        // 以下配置参数来自服务端 SDK，这是第一次验证，也就是获取到了后端的这个三个数据
                        gt: response.data.gt,
                        challenge: response.data.challenge,
                        offline: !response.data.success,
                        width: '100%',
                        // 宕机情况下使用，表示验证是 3.0 还是 2.0，3.0 的 sdk 该字段为 true
                        new_captcha: true

                    }, (captchaObj) => {
                        captchaObj.appendTo('#geetest');
                        captchaObj.onReady(function () {
                            $("#wait")[0].className = "hide";
                        });

                        captchaObj.onSuccess(() => {
                            let result = captchaObj.getValidate();
                            this.$emit("getGeet", result);
                        });
                    })
                }).catch(err => {
                    // to do
                });
            }
        }
    }

</script>