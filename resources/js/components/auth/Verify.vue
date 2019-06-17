<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form class="fh5co-form" @submit.prevent="verify">

                    <div style="float: left; width: 320px">
                        <img src="https://coding.imooc.com/static/module/class/content/img/311/section2-6.png" width="170px"/>
                        <img src="https://coding.imooc.com/static/module/class/content/img/219/section1-1.png" width="220px" style="margin-left: 130px; margin-top: 20px"/>
                    </div>

                    <div style="float: right; width: 300px; height: 313px;">
                        <h4 style="color: #67838c; font-weight: bold; margin-bottom: 19px">完善信息</h4>

                        <!-- 用户名 -->
                        <div class="form-group">
                            <input v-validate="'required|username'" v-model="username" :class="[{ 'is-invalid' : errors.has('username') }, 'form-control']" type="text" name="username" placeholder="eg: 14-电气-黄树斌 (级数-专业-姓名)">
                            <span class="invalid-feedback" v-if="errors.has('username')">{{ errors.first('username') }}</span>
                            <span class="message" v-if="!errors.has('username')">{{ usererror }}</span>
                        </div>

                        <!-- 密码 -->
                        <div class="form-group">
                            <input v-validate="'required|password'" v-model="password" :class="[{'is-invalid' : errors.has('password')}, 'form-control']" type="password" name="password" placeholder="6-16位包含字母数字的密码">
                            <span class="invalid-feedback" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                        </div>

                        <!-- 短信验证码 -->
                        <div class="form-group" style="position: relative;">
                            <input v-validate="'required|smscode'" v-model="smscode" type="text" name="smscode" :class="[{'is-invalid' : errors.has('smscode')}, 'form-control', 'sms_code']" placeholder="请输入短信验证码">
                            <span class="invalid-feedback" v-if="errors.has('smscode')">{{ errors.first('smscode') }}</span>
                            <span class="message" v-if="!errors.has('smscode')">{{ smserror }}</span>
                            <div style="position: absolute; right: 0; top: 0">
                                <input type="button" :class="[{'disabled': isOvertime}, 'btn', 'send_sms']" @click="sendSms" :value="word">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block common">完成注册</button>
                        </div>

                        <!-- 返回修改手机号 -->
                        <div class="fa-pull-right">
                            <router-link to="/register">返回修改手机号 <i class="fas fa-undo"></i></router-link>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                </form>
            </div>
        </div>

        <!--<div class="row sign">-->
            <!--<div class="col-md-12 text-center"><p><small>&copy; Inspiration comes from life!</small></p></div>-->
        <!--</div>-->
    </div>
</template>

<script>
    export default {

        data() {
            return {
                word: '重新发送',
                phone: '',
                smscode: '',
                username: '',
                password: '',
                smserror: '',
                usererror: '',
                isOvertime: false,
            }
        },

        watch: {
            smscode() {
                this.smserror = '';
            },

            username() {
                this.usererror = '';
            }
        },

        created() {
            this.phone = sessionStorage.getItem('phone');
        },

        mounted() {
            this.timeKeep();
        },

        methods: {
            back() {
                this.$router.push({name:'register'});
            },

            timeKeep() {
                let time = 10;
                this.smscode = '';
                this.isOvertime = true;
                time--;
                this.word = time + ' s';
                let sendTimer = setInterval(() => {
                    time--;
                    this.word = time + ' s';
                    if(time < 0){
                        this.isOvertime = false;
                        clearInterval(sendTimer);
                        this.word = "重新发送";
                    }
                },1000);
            },

            sendSms() {

                if(this.isOvertime) {
                    return false;
                }

                this.timeKeep();

                 let formData = {phone: this.phone};
                 this.$api.auth.smscode(formData).then(response => {
                     sessionStorage.setItem("verify_key", response.data.verify_key);
                 }).catch(error => {
                     if(error.response.status === 500){
                         this.$swal.fire({
                             type: 'error',
                             text: '哎呀！网络连接出错了...'
                         });
                     }
                 });
            },

            verify() {
                this.$validator.validateAll().then((result) => {
                    if(result) {

                        let formData = {
                            smscode: this.smscode,
                            username: this.username,
                            password: this.password,
                            verify_key: sessionStorage.getItem('verify_key'),
                        };

                        this.$store.dispatch('certification/register', formData).then(() => {
                            sessionStorage.removeItem('phone');
                            this.$router.push({name: 'home'});
                        }).catch(error => {
                            if(error.response.data.errors.smscode) {
                                this.smserror = error.response.data.errors.smscode;
                            }
                            if(error.response.data.errors.username) {
                                this.usererror = error.response.data.errors.username;
                            }
                        });
                    }
                });
            }
        }
    }
</script>

<style scoped>
    .container {
        min-width: 1100px;
    }

    .sms_code {
        width: 65%;
    }

    .common{
        /*color: #606060;*/
        /*box-shadow: none;*/
        /*border-color: #ced4da;*/
        /*margin-bottom: 15px;*/
        color: #ffffff;
        border-color: #4d94a0;
        background-color: #4d94a0;
        box-shadow: -2px 2px 5px 2px rgba(78, 173, 185, 0.1);
    }

    .common:hover{
        /*color: #32c6c6;*/
        /*border-color: #32c6c6;*/
        /*background-color: rgba(200, 255, 193, 0);*/
        border-color: #50a3af;
        background-color: #50a3af;
    }

    .send_sms{
        width: 82px;
        color: #ffffff;
        border-color: #4d94a0;
        background-color: #4d94a0;
        box-shadow: -2px 2px 5px 2px rgba(78, 173, 185, 0.1);
    }

    .send_sms:hover{
        background-color: #50a3af;
    }

    a {
        color: #45555d;
    }

    a:hover {
        color: #54a3af;
    }

    /*.sign {*/
        /*clear: both;*/
        /*padding-top: 35px;*/
    /*}*/
</style>