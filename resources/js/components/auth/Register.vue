<template>

    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-7">
                <!-- Start Sign In Form -->
                <form class="fh5co-form" @submit.prevent="register">
                    <h2>Sign Up</h2>

                    <div class="form-group">
                        <div class="alert alert-success" role="alert">Your info has been saved!</div>
                    </div>

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
                        <a href="#">
                            <input value="GitHub登录" type="button" class="btn btn-block github">
                        </a>
                    </div>

                    <div class="form-group fa-pull-right">
                        <p>已经注册了? <router-link to="/login">登录</router-link></p>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn register">Sign Up <i class="fas fa-sign-in-alt"></i></button>
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

    import GeetTest from '../validate/GeetTest'

    export default {
        components: {
            GeetTest
        },

        data() {
            return {
                phone: '',
                noGeet: false,
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

                        this.$axios.post('/api/signup', formData).then(response => {
                            sessionStorage.setItem("phone", this.phone);
                            this.$router.push({name:'verify'});
                        }).catch(error => {
                            console.log(error.response);
                            if(error.response.data.errors.geetest_challenge){
                                this.geet_message = '验证模块异常,请重新验证';
                                this.noGeet = true;
                                this.geetestObj = null;
                                this.captchaObj.reset();
                            }
                            if(error.response.data.errors.phone){
                                this.phone_message = error.response.data.errors.phone[0];
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

    .github{
        color: #606060;
        box-shadow: none;
        border-color: #ced4da;
    }

    .github:hover{
        color: #32c6c6;
        border-color: #32c6c6;
        background-color: rgba(200, 255, 193, 0);
    }

    .register{
        color: #ffffff;
        border-color: #33cccc;
        background-color: #33cdcd;
        box-shadow: -4px 7px 20px 2px rgba(53, 212, 212, 0.2);
    }

    .register:hover{
        background-color: #34d7d7;
    }

    .sign {
        clear: both;
        padding-top: 168px;
    }
</style>