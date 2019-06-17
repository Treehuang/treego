<template>

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Start Sign In Form -->
                <form class="fh5co-form" @submit.prevent="register">

                    <div style="float: left; width: 320px">
                        <img src="https://coding.imooc.com/static/module/class/content/img/311/section2-6.png" width="170px"/>
                        <img src="https://coding.imooc.com/static/module/class/content/img/219/section1-1.png" width="220px" style="margin-left: 130px; margin-top: 20px"/>
                    </div>

                    <div style="float: right; width: 300px">
                        <h4 style="color: #67838c; font-weight: bold; margin-bottom: 19px">注册</h4>

                        <div class="form-group">
                            <input v-validate="'required|phone'" v-model="phone" type="text" :class="[{'is-invalid' : errors.has('phone')}, 'form-control']" name="phone" placeholder="请输入手机号">
                            <span class="invalid-feedback" v-if="errors.has('phone')">{{ errors.first('phone') }}</span>
                            <span class="message" v-if="!errors.has('phone')">{{ phone_message }}</span>
                        </div>

                        <div class="form-group">
                            <geet-test @getGeet="getGeetTestObj" message="register_geetest"></geet-test>
                            <span class="message" v-if="noGeet">{{ geet_message }}</span>
                        </div>
                        <!-- github登录 -->
                        <div class="form-group">
                            <button type="button" class="btn btn-block github">GitHub登录</button>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block register" :disabled="isDisable">注册 <i :class="[isloading ? 'spinner-border spinner-border-sm' : 'fas fa-sign-in-alt']"></i></button>
                        </div>

                        <div class="fa-pull-right">
                            <p>已经注册了? <router-link to="/login">登录</router-link></p>
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
                phone: '',
                noGeet: false,
                isloading: false,
                isDisable: false,
                geet_message: '请完成验证操作',
                phone_message: '',
                geetestObj: null,
                captchaObj: null,
            }
        },

        watch: {
            phone() {
                this.phone_message = '';
            }
        },

        methods: {
            register() {
                this.noGeet = !this.geetestObj;
                //校验输入框数据
                this.$validator.validateAll().then((result) => {
                    if(result && !this.noGeet) {

                        let formData = Object.assign(this.geetestObj, {phone : this.phone})

                        this.isDisable = true;
                        this.isloading = true;

                        this.$api.auth.signup(formData).then(response => {
                            sessionStorage.setItem("phone", this.phone);
                            sessionStorage.setItem("verify_key", response.data.verify_key);
                            this.$router.push({name:'verify'});
                        }).catch(error => {
                            console.log(error.response);
                            if(error.response.status == 429) {
                                this.$swal.fire({
                                    'type': 'warning',
                                    'text': '您操作太频繁了，请稍等1分钟再操作~'
                                }).then((result) => {
                                    if(result.value) {
                                        this.isDisable = false;
                                        this.isloading = false;
                                        this.geetestObj = null;
                                        this.captchaObj.reset();
                                    }
                                });

                                return;
                            }

                            if(error.response.status == 500){
                                this.$swal.fire({
                                    type: 'error',
                                    text: '哎呀！网络连接出错了...'
                                }).then(result => {
                                    if(result.value){
                                        this.isDisable = false;
                                        this.isloading = false;
                                        this.geetestObj = null;
                                        this.captchaObj.reset();
                                    }
                                });

                                return;
                            }
                            if(error.response.data.errors.geetest_challenge){
                                this.isDisable = false;
                                this.isloading = false;
                                this.geet_message = '验证模块异常,请重新验证';
                                this.noGeet = true;
                                this.geetestObj = null;
                                this.captchaObj.reset();
                            }
                            if(error.response.data.errors.phone){
                                this.isDisable = false;
                                this.isloading = false;
                                this.geetestObj = null;
                                this.phone_message = error.response.data.errors.phone;
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
        }
    }
</script>

<style scoped>
    .container {
        min-width: 1100px;
    }

    .fa-sign-in-alt {
        margin-left: 2px;
    }

    .github{
        color: #606060;
        box-shadow: none;
        border-color: #ced4da;
    }

    .github:hover{
        color: #4b8f9b;
        border-color: #50a3af;
    }

    .register{
        color: #ffffff;
        border-color: #4d94a0;
        background-color: #4d94a0;
        box-shadow: -2px 2px 5px 2px rgba(78, 173, 185, 0.1);
    }

    .register:hover{
        border-color: #50a3af;
        background-color: #50a3af;
    }

    a {
        color: #45555d;
    }

    a:hover {
        color: #54a3af;
    }
</style>