<template>
    <div>

        <i class="fas fa-shield-alt"></i>
        <div class="password">
            <p class="info">
                <span class="match">密码</span>
                <span>已设置</span>
            </p>
            <p class="prompt">用于保护账号信息和登录安全</p>
        </div>

        <button class="btn secondary" data-toggle="modal" data-target="#updatePass">修改</button>

        <div class="modal" id="updatePass" tabindex="-1" role="dialog" aria-labelledby="updatePass" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 style="float: left; color: #545454">修改密码</h5>

                        <div class="clearfix"></div>

                        <form @submit.prevent="updatePass">
                            <div style="margin-bottom: 20px">
                                <div>
                                    <label for="old" class="label">输入原密码</label>
                                    <input v-validate="'required|oldpassword'" :class="[{'is-invalid' : errors.has('oldpassword')}, 'form-control']" v-model="oldPass" name="oldpassword" id="old" type="password" placeholder="6-16位包含字母数字的密码">
                                    <span class="invalid-feedback" v-if="errors.has('oldpassword')">{{ errors.first('oldpassword') }}</span>
                                    <span class="message pass_error" v-if="!errors.has('oldpassword')">{{ pass_error }}</span>
                                </div>

                                <div>
                                    <label for="new" class="label">输入新密码</label>
                                    <input v-validate="'required|newpassword'" :class="[{'is-invalid' : errors.has('newpassword')}, 'form-control']" v-model="newPass" name="newpassword" id="new" type="password" placeholder="6-16位包含字母数字的密码">
                                    <span class="invalid-feedback" v-if="errors.has('newpassword')">{{ errors.first('newpassword') }}</span>
                                </div>

                                <div>
                                    <label for="news" class="label">确认新密码</label>
                                    <input v-validate="{'required': 'true', 'is': newPass}" :class="[{'is-invalid' : errors.has('repassword')}, 'form-control']" name="repassword" id="news" type="password" placeholder="6-16位包含字母数字的密码">
                                    <span class="invalid-feedback" v-if="errors.has('repassword')">{{ errors.first('repassword') }}</span>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button class="btn light" data-dismiss="modal">取消</button>
                                <button type="submit" class="btn btn-primary">确定</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: 'UpdatePassword',

        data() {
            return {
                oldPass: '',
                newPass: '',
                pass_error: '',
            }
        },

        watch: {
            oldPass() {
                this.pass_error = '';
            }
        },

        methods: {
            updatePass() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        let formData = { oldPassword: this.oldPass, newPassword: this.newPass };
                        this.$api.auth.updatePassword(formData).then(() => {
                            // 关闭模态框
                            $("#updatePass").modal('hide');

                            this.$swal.fire({
                                type: 'success',
                                title: '修改密码成功',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 2000,
                            }).then();

                        }).catch(error => {
                            if (error.response.data.errors.password) {
                                this.pass_error = error.response.data.errors.password
                            } else {
                                this.$swal.fire({
                                    type: 'error',
                                    title: '哎呀，网络错误，修改失败~',
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                }).then();
                            }
                        })
                    }
                })
            },
        }
    }
</script>

<style scoped>
    .fa-shield-alt {
        float: left; margin-left: 20px; margin-right: 20px; font-size: 27px; color: #4c94a0
    }

    .password {
        float: left; margin-right: 419px
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

    .match {
        font-size: 17px;
    }

    .form-control {
        width: 65%;
        margin-top: 10px;
    }

    .label {
        float: left; font-size: 15px; margin-right: 30px; margin-top: 6px; margin-left: 20px; color: #636b6f;
    }

    .btn-primary {
        font-size: 16px;
    }

    .light {
        color: #ffffff;
        font-size: 16px;
        background-color: #aaaaaa;
    }

    .light:hover {
        background-color: #a0a0a0;
    }

    .modal-body {
        padding-bottom: 0;
    }

    .invalid-feedback, .pass_error {
        margin-left: 125px;
    }
</style>