<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-7">
                <form class="fh5co-form" @submit.prevent="verify">

                    <h2>Complete Info</h2>

                    <div class="form-group">
                        <div class="alert alert-success" role="alert">短信验证码已发送至 +86 <span>{{ phone }}</span></div>
                    </div>

                    <!-- 用户名 -->
                    <div class="form-group">
                        <input v-validate="'required|username'" v-model="username" :class="[{ 'is-invalid' : errors.has('username') }, 'form-control']" type="text" name="username" placeholder="14-电气-黄树斌 (级数-专业-姓名)">
                        <span class="invalid-feedback" v-if="errors.has('username')">{{ errors.first('username') }}</span>
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
                        <div style="position: absolute; right: 0; top: 0">
                            <input type="button" :class="[{'disabled': isOvertime}, 'btn', 'send_sms']" @click="sendSms" :value="word">
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block common">完成</button>
                    </div>

                    <!-- 返回修改手机号 -->
                    <button type="button" class="btn btn-block common" @click="back">返回修改手机号</button>
                </form>
            </div>
        </div>
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
                isOvertime: false,
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
                 this.$axios.post('/api/smscode', formData).then(response => {
                     console.log(response.config);
                 }).catch(error => {

                 });
            },

            verify() {
                this.$validator.validateAll().then((result) => {
                    if(result) {
                        console.log('success');
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
        color: #606060;
        box-shadow: none;
        border-color: #ced4da;
    }

    .common:hover{
        color: #32c6c6;
        border-color: #32c6c6;
        background-color: rgba(200, 255, 193, 0);
    }

    .send_sms{
        width: 82px;
        color: #ffffff;
        border-color: #33cccc;
        background-color: #33cdcd;
        box-shadow: -4px 7px 20px 2px rgba(53, 212, 212, 0.2);
    }

    .send_sms:hover{
        background-color: #34d7d7;
    }
</style>