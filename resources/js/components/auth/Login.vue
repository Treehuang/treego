<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-7">

                <form class="fh5co-form" @submit.prevent="login">
                    <h2>Sign In</h2>

                    <div class="form-group">
                        <div class="alert alert-success" role="alert">You will be logged into your account!</div>
                    </div>

                    <div class="form-group">
                        <input v-validate="'required|account'" v-model="account" type="text" :class="[{'is-invalid' : errors.has('account')}, 'form-control']" name="account" placeholder="手机号/邮箱">
                        <span class="invalid-feedback" v-if="errors.has('account')">{{ errors.first('account') }}</span>
                    </div>

                    <div class="form-group">
                        <input v-validate="'required|password'" v-model="password" type="password" :class="[{'is-invalid' : errors.has('password')}, 'form-control']" name="password" placeholder="密码">
                        <span class="invalid-feedback" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                    </div>

                    <div class="form-group">
                        <geet-test @getGeet="getGeetTestObj" message="login_geetest"></geet-test>
                        <span class="message" v-if="noGeet">{{ message }}</span>
                    </div>

                    <div class="fa-pull-left check">
                        <input class="form-check-input" type="checkbox" value="check" id="check">
                        <label class="form-check-label" for="check">
                            记住我
                        </label>
                    </div>

                    <div class="fa-pull-right">
                        <p><router-link to="/register"> 注册 </router-link> / <router-link to="#">忘记密码?</router-link></p>
                    </div>

                    <div class="clearfix"></div>

                    <div class="form-group">
                        <!-- 登录按钮 -->
                        <div class="form-group">
                            <button type="submit" class="btn login"><span>Sign In <i :class="[isloading ? 'spinner-border spinner-border-sm' : 'fas fa-sign-in-alt']"></i></span></button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="row sign">
            <div class="col-md-12 text-center"><p><small>&copy; Inspiration comes from life!</small></p></div>
        </div>
    </div>
</template>

<script>

    import  GeetTest from '../validate/GeetTest'

    export default {
        components: {
            GeetTest
        },

        data() {
            return {
                account : '',
                password: '',
                noGeet: false,
                isloading: false,
                message: '请完成验证操作',
                geetestObj: null,
                captchaObj: null,
            }
        },

        methods: {

            login() {

                this.noGeet = !this.geetestObj;

                // 校验所有,只要有一个校验失败，就返回false
                this.$validator.validateAll().then((result) => {
                    if(result && !this.noGeet) {

                        let formData = Object.assign(this.geetestObj, {account: this.account, password: this.password});

                        this.isloading = true;
                        axios.post('/api/login', formData).then(response => {
                            // TO DO
                            this.$router.push({name:'home'});

                        }).catch(error => {
                            this.isloading = false;
                            this.message = '验证模块异常,请重新验证';
                            this.noGeet = true;
                            this.geetestObj = null;
                            this.captchaObj.reset();
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

    .check {
        color: #636b6f;
        padding-left: 1.3rem;
    }

    .login{
        color: #ffffff;
        border-color: #33cccc;
        background-color: #33cdcd;
        box-shadow: -4px 7px 20px 2px rgba(53, 212, 212, 0.2);
    }

    .login:hover{
        background-color: #34d7d7;
    }

    .sign {
        clear: both;
        padding-top: 130px;
    }
</style>