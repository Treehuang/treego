<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form class="fh5co-form" @submit.prevent="login">

                    <div style="float: left; width: 320px">
                        <img src="https://coding.imooc.com/static/module/class/content/img/311/section2-6.png" width="170px"/>
                        <img src="https://coding.imooc.com/static/module/class/content/img/219/section1-1.png" width="220px" style="margin-left: 130px; margin-top: 20px"/>
                    </div>
                    
                    <div style="float: right; width: 300px">
                        <h4 style="color: #67838c; font-weight: bold; margin-bottom: 19px">登录</h4>

                        <div class="form-group">
                            <input v-validate="'required|account'" v-model="account" type="text" :class="[{'is-invalid' : errors.has('account')}, 'form-control']" name="account" placeholder="手机号/邮箱">
                            <span class="invalid-feedback" v-if="errors.has('account')">{{ errors.first('account') }}</span>
                            <span class="message" v-if="!errors.has('account')">{{ account_error }}</span>
                        </div>

                        <div class="form-group">
                            <input v-validate="'required|password'" v-model="password" type="password" :class="[{'is-invalid' : errors.has('password')}, 'form-control']" name="password" placeholder="密码">
                            <span class="invalid-feedback" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                        </div>

                        <div class="form-group">
                            <geet-test @getGeet="getGeetTestObj" message="login_geetest"></geet-test>
                            <span class="message" v-if="noGeet">{{ message }}</span>
                        </div>

                        <div class="form-group">
                            <!-- 登录按钮 -->
                            <button type="submit" class="btn btn-block login" :disabled="isDisable"><span>登录 <i :class="[isloading ? 'spinner-border spinner-border-sm' : 'fas fa-sign-in-alt']"></i></span></button>
                        </div>

                        <div class="fa-pull-left check">
                            <input class="form-check-input" type="checkbox" v-model="check" id="check" style="display: none">
                            <label class="form-check-label" for="check"></label>
                            <span class="seven">7天内自动登录</span>
                        </div>

                        <div class="fa-pull-right">
                            <p><router-link tag="a" to="/register"> 注册 </router-link> / <router-link tag="a" to="#">忘记密码?</router-link></p>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                </form>
            </div>
        </div>
    </div>
</template>

<script>

    import GeetTest from '../validate/GeetTest'

    export default {
        components: {
            GeetTest
        },

        data() {
            return {
                check: false,
                account : '',
                password: '',
                noGeet: false,
                isloading: false,
                isDisable: false,
                account_error: '',
                message: '请完成验证操作',
                geetestObj: null,
                captchaObj: null,
            }
        },

        watch: {
            account() {
                this.account_error = '';
            }
        },

        methods: {

            login() {

                this.noGeet = !this.geetestObj;

                // 校验所有,只要有一个校验失败，就返回false
                this.$validator.validateAll().then((result) => {
                    if(result && !this.noGeet) {

                        let formData = Object.assign(this.geetestObj, {account: this.account, password: this.password});

                        this.isDisable = true;
                        this.isloading = true;

                        this.$store.dispatch('certification/login', formData).then(() => {
                            // TO DO
                            this.$router.push({name:'home'});

                        }).catch(error => {
                            if(error.response.status === 500) {

                                this.isDisable = false;
                                this.isloading = false;
                                this.geetestObj = null;
                                this.captchaObj.reset();

                                this.$swal.fire({
                                    type: 'error',
                                    title: '哎呀！网络连接出错了...',
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                }).then();

                                return;
                            }

                            if(error.response.status === 429) {

                                this.isDisable = false;
                                this.isloading = false;
                                this.geetestObj = null;
                                this.captchaObj.reset();

                                this.$swal.fire({
                                    type: 'warning',
                                    text: '哎呀！您今天的尝试登录次数已经耗光啦☹',
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                }).then();

                                return;
                            }

                            // 检测还剩多少次登录的机会
                            if(error.response.headers['x-ratelimit-remaining'] <= 3) {

                                if (error.response.headers['x-ratelimit-remaining'] === 0) {
                                    this.$swal.fire({
                                        type: 'warning',
                                        title: '哎呀！您今天的尝试登录次数已经耗光啦☹',
                                        toast: true,
                                        position: 'top',
                                        showConfirmButton: false,
                                        timer: 3000,
                                    }).then();
                                }else {
                                    this.$swal.fire({
                                        type: 'warning',
                                        title: '您今天还有' + error.response.headers['x-ratelimit-remaining'] + '次登录尝试',
                                        toast: true,
                                        position: 'top',
                                        showConfirmButton: false,
                                        timer: 3000,
                                    }).then();
                                }
                            }

                            if(error.response.data.errors.geetest_challenge) {
                                this.isDisable = false;
                                this.isloading = false;
                                this.message = '验证模块异常,请重新验证';
                                this.noGeet = true;
                                this.geetestObj = null;
                                this.captchaObj.reset();
                            }

                            if(error.response.data.errors.account){
                                this.isDisable = false;
                                this.isloading = false;
                                this.geetestObj = null;
                                this.account_error = error.response.data.errors.account;
                                this.captchaObj.reset();
                            }
                        });
                    }
                });
            },

            // 极验子组件传递数据
            getGeetTestObj: function(data) {
                this.noGeet = false;
                this.geetestObj = data[0];
                this.captchaObj = data[1];
            }
        },
    }
</script>

<style scoped>
    .container {
        min-width: 1100px;
    }

    .fa-sign-in-alt {
        margin-left: 2px;
    }

    .btn {
        min-width: 75px;
    }

    .login{
        color: #ffffff;
        border-color: #4d94a0;
        background-color: #4d94a0;
        box-shadow: -2px 2px 5px 2px rgba(78, 173, 185, 0.1);
    }

    .login:hover{
        border-color: #50a3af;
        background-color: #50a3af;
    }

    a, .form-check-label {
        color: #45555d;
    }

    a:hover {
        color: #54a3af;
    }

    .form-check-input {
        color: #45555d !important;
    }

    .seven {
        color: #45555d;
        margin-left: 20px;
    }

    .check {
        position: relative;
    }

    .check label {
        cursor: pointer;
        position: absolute;
        width: 14px;
        height: 14px;
        top: 0;
        left: 0;
        background: #eee;
        border:1px solid #ddd;
        margin-top: 4px;
    }

    .check label:after {
        opacity: 0.2;
        content: '';
        position: absolute;
        width: 9px;
        height: 5px;
        background: transparent;
        top: 3px;
        left: 2px;
        border: 3px solid #333;
        border-top: none;
        border-right: none;

        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        -ms-transform: rotate(-45deg);
        transform: rotate(-45deg);
    }

    /**
     * Create the hover event of the tick
     */
    .check label:hover::after {
        opacity: 0.5;
    }

    /**
     * Create the checkbox state for the tick
     */
    .check input[type=checkbox]:checked + label:after {
        opacity: 1;
    }
</style>