<template>
    <div>
        <div v-if="myEmail">
            <i class="far fa-envelope"></i>
            <div class="email">
                <p class="info">
                    <span class="match">邮箱</span>
                    <span style="color: #1b829d">{{ myEmail }}</span>
                    <span>已经绑定</span>
                </p>
                <p class="prompt">可用邮箱加密码登录 tree go，可用邮箱找回密码</p>
            </div>
            <button class="btn secondary" @click="unbindEmail">解绑</button>
        </div>

        <div v-else>
            <i class="far fa-envelope"></i>
            <div class="email">
                <p class="info" >
                    <span class="match">邮箱</span>
                    <span>您还未绑定，请点击绑定</span>
                </p>
                <p class="prompt">可用邮箱加密码登录 tree go，可用邮箱找回密码</p>
            </div>
            <button class="btn secondary" @click="bindEmail">绑定</button>
        </div>

        <!--输入密码验证身份-->
        <div class="modal" id="unbindEmail" tabindex="-1" role="dialog" aria-labelledby="unbindEmail" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">解绑邮箱</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">原来已绑定的邮箱</span>
                                <span class="unbindEmail">{{ myEmail }}</span>
                            </div>

                            <div class="clearfix"></div>

                            <div style="margin-top: 20px">
                                <label for="password" class="label">输入密码验证身份</label>
                                <input v-validate="'required|password'" :class="[{'is-invalid' : errors.has('password')}, 'form-control']" v-model="password" name="password" id="password" type="password">
                                <span class="invalid-feedback" v-if="errors.has('password')">{{ errors.first('password') }}</span>
                                <span class="message password_message" v-if="!errors.has('password')">{{ password_message }}</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button class="btn next" @click="nextOne" :disabled="isDisable"><span v-show="!loading">下一步</span><i v-show="loading" class="spinner-border spinner-border-sm"></i></button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--输入验证码-->
        <div class="modal" id="unbindEmailCode" tabindex="-1" role="dialog" aria-labelledby="unbindEmailCode" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">解绑邮箱</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">
                            <div>
                                <span class="label">原来已绑定的邮箱</span>
                                <span class="unbindEmail">{{ myEmail }}</span>
                            </div>

                            <div class="clearfix"></div>

                            <div style="margin-top: 20px">
                                <label for="emailcode" class="label">输入收到的验证码</label>
                                <input v-validate="'required|emailcode'" :class="[{'is-invalid' : errors.has('emailcode')}, 'form-control']" v-model="emailcode" name="emailcode" id="emailcode" type="text">
                                <span class="invalid-feedback" v-if="errors.has('emailcode')">{{ errors.first('emailcode') }}</span>
                                <span class="message password_message" v-if="!errors.has('emailcode')">{{ emailcode_message }}</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button class="btn next" @click="nextTwo">下一步</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--确认是否解绑该邮箱-->
        <div class="modal" id="sureUnbindEmail" tabindex="-1" role="dialog" aria-labelledby="sureUnbindEmail" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">解绑邮箱</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">确定解绑该邮箱</span>
                                <span class="unbindEmails">{{ myEmail }} ？</span>
                            </div>

                            <div class="clearfix"></div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button class="btn next" @click="sureUnbind">确定</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!--输入要绑定的邮箱-->
        <div class="modal" id="bindEmail" tabindex="-1" role="dialog" aria-labelledby="bindEmail" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">绑定邮箱</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">绑定后，您可以使用此邮箱进行找回密码及登录的相关操作</span>
                            </div>

                            <div class="clearfix"></div>

                            <div style="margin-top: 20px">
                                <label for="bindemails" class="label">输入要绑定的邮箱</label>
                                <input v-validate="'required|bindemail'" :class="[{'is-invalid' : errors.has('bindemail')}, 'form-control']" v-model="bindemail" name="bindemail" id="bindemails" type="text">
                                <span class="invalid-feedback" v-if="errors.has('bindemail')">{{ errors.first('bindemail') }}</span>
                                <span class="message password_message" v-if="!errors.has('bindemail')">{{ bindemail_message }}</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button class="btn next" @click="bindNextOne" :disabled="isDisable"><span v-show="!loading">下一步</span><i v-show="loading" class="spinner-border spinner-border-sm"></i></button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--输入验证码-->
        <div class="modal" id="bindEmailCode" tabindex="-1" role="dialog" aria-labelledby="bindEmailCode" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">绑定邮箱</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">绑定后，您可以使用此邮箱进行找回密码及登录的相关操作</span>
                            </div>

                            <div class="clearfix"></div>

                            <div style="margin-top: 20px">
                                <label for="bindemailcodes" class="label">输入收到的验证码</label>
                                <input v-validate="'required|bindemailcode'" :class="[{'is-invalid' : errors.has('bindemailcode')}, 'form-control']" v-model="bindemailCode" name="bindemailcode" id="bindemailcodes" type="text">
                                <span class="invalid-feedback" v-if="errors.has('bindemailcode')">{{ errors.first('bindemailcode') }}</span>
                                <span class="message password_message" v-if="!errors.has('bindemailcode')">{{ bindemailcode_message }}</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button class="btn next" @click="bindNextTwo">下一步</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--确认是否绑定该邮箱-->
        <div class="modal" id="sureBindEmail" tabindex="-1" role="dialog" aria-labelledby="sureBindEmail" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">解绑邮箱</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">确认绑定该邮箱</span>
                                <span class="unbindEmails">{{ bindemail }} ？</span>
                            </div>

                            <div class="clearfix"></div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button class="btn next" @click="sureBind">确定</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'UpdateEmial',

        computed: {
            ...mapState({
                myEmail : state => state.certification.email,
            })
        },

        watch: {
            password() {
                this.password_message = '';
            },

            emailcode() {
                this.emailcode_message = '';
            },

            bindemail() {
                this.bindemail_message = '';
            },

            bindemailCode() {
                this.bindemailcode_message = '';
            }
        },

        data() {
            return {
                loading: false,
                isDisable: false,
                password: null,
                emailcode: null,
                bindemail: null,
                bindemailCode: null,
                password_message: null,
                emailcode_message: null,
                bindemail_message: null,
                bindemailcode_message: null,
            }
        },

        methods: {

            unbindEmail() {
                // 进行查库，看是不是有emial,如果没有email,不给解绑手机
                this.$api.auth.me().then(response => {
                    if(response.data.phone) {
                        $('#unbindEmail').modal('show');
                    }else {
                        this.$swal.fire({
                            type: 'warning',
                            title: 'sorry， 您未绑定手机，无法解绑该邮箱~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                        })
                    }
                }).catch();
            },

            nextOne() {
                this.$validator.validate('password').then((result) => {
                    if (result) {

                        this.isDisable = true;
                        this.loading = true;

                        // 发送邮件验证码
                        let formData = { password: this.password };
                        this.$api.auth.unbindEmailCode(formData).then(response => {
                            sessionStorage.setItem("verify_key", response.data.verify_key);
                            $('#unbindEmail').modal('hide');
                            $('#unbindEmailCode').modal('show');
                            this.isDisable = false;
                            this.loading = false;
                        }).catch(error => {
                            this.isDisable = false;
                            this.loading = false;
                            this.password_message = error.response.data.errors.password;
                        })
                    }
                })
            },

            nextTwo() {
                this.$validator.validate('emailcode').then((result) => {
                    if (result) {
                        let formData = { email_code: this.emailcode, verify_key: sessionStorage.getItem('verify_key') };
                        this.$api.auth.checkEmailCode(formData).then(() => {
                            $('#unbindEmailCode').modal('hide');
                            $('#sureUnbindEmail').modal('show');
                        }).catch(error => {
                            this.emailcode_message = error.response.data.errors.code;
                        });
                    }
                })
            },

            sureUnbind() {
                this.$api.auth.unbindEmail().then(response => {
                    this.$store.commit('certification/updateUser', response.data);
                    $('#sureUnbindEmail').modal('hide');
                    this.$swal.fire({
                        type: 'success',
                        title: '解绑成功',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                    }).then();
                }).catch(() => {
                    ('#sureUnbindEmail').modal('hide');
                    this.$swal.fire({
                        type: 'error',
                        title: '哎呀，网络问题，解绑失败~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                    });
                });
            },

            bindEmail() {
                $('#bindEmail').modal('show');
            },

            bindNextOne() {
                this.$validator.validate('bindemail').then((result) => {
                    if (result) {

                        this.isDisable = true;
                        this.loading = true;

                        let formData = { email: this.bindemail };
                        this.$api.auth.bindEmailCode(formData).then(response => {
                            sessionStorage.setItem("verify_key", response.data.verify_key);
                            $('#bindEmail').modal('hide');
                            $('#bindEmailCode').modal('show');
                            this.isDisable = false;
                            this.loading = false;
                        }).catch(error => {
                            this.isDisable = false;
                            this.loading = false;
                            this.bindemail_message = error.response.data.errors.password;
                        })
                    }
                })
            },

            bindNextTwo() {
                this.$validator.validate('bindemailcode').then((result) => {
                    if (result) {
                        let formData = { email_code: this.bindemailCode, verify_key: sessionStorage.getItem('verify_key') };
                        this.$api.auth.checkEmailCode(formData).then(() => {
                            $('#bindEmailCode').modal('hide');
                            $('#sureBindEmail').modal('show');
                        }).catch(error => {
                            this.bindemailcode_message = error.response.data.errors.code;
                        });
                    }
                })
            },

            sureBind() {
                let formData = { email: this.bindemail };
                this.$api.auth.bindEmail(formData).then(response => {
                    this.$store.commit('certification/updateUser', response.data);
                    $('#sureBindEmail').modal('hide');
                    this.$swal.fire({
                        type: 'success',
                        title: '绑定成功',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                    }).then();
                }).catch(() => {
                    this.$swal.fire({
                        type: 'error',
                        title: '哎呀，网络问题，绑定失败~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                    });
                });
            },

            // bindEmail() {
            //     this.$swal.mixin({
            //         animation: false,
            //         allowOutsideClick: false,
            //         cancelButtonText: '取消',
            //         confirmButtonText: '下一步',
            //         showCancelButton: true,
            //         progressSteps: ['1', '2', '3']
            //     }).queue([
            //         {
            //             text: '请输入要绑定的邮箱',
            //             input: 'text',
            //             inputAutoTrim: true,
            //             reverseButtons: true,
            //             showLoaderOnConfirm: true,
            //             inputValidator: (value) => {
            //                 return new Promise((resolve) => {
            //                     if (/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
            //                         resolve()
            //                     } else {
            //                         resolve('邮箱格式错误')
            //                     }
            //                 })
            //             },
            //             preConfirm: (value) => {
            //                 // 发送邮件验证码
            //                 let formData = { email: value };
            //                 return this.$api.auth.bindEmailCode(formData).then(response => {
            //                     this.bind_email = value;
            //                     sessionStorage.setItem("verify_key", response.data.verify_key);
            //                 }).catch(error => {
            //                     this.$swal.showValidationMessage(error.response.data.errors.password);
            //                 })
            //             }
            //         },
            //         {
            //             text: '请输入验证码',
            //             input: 'text',
            //             inputAutoTrim: true,
            //             reverseButtons: true,
            //             inputValidator: (value) => {
            //                 return new Promise((resolve) => {
            //                     if (/^[0-9]{6}$/.test(value)) {
            //                         resolve()
            //                     } else {
            //                         resolve('请输入正确的验证码')
            //                     }
            //                 })
            //             },
            //             preConfirm: (value) => {
            //                 let formData = { email_code: value, verify_key: sessionStorage.getItem('verify_key') };
            //                 return this.$api.auth.checkEmailCode(formData).then(() => {}).catch(error => {
            //                     this.$swal.showValidationMessage(error.response.data.errors.code);
            //                 });
            //             },
            //         },
            //         {
            //             text: '确定绑定邮箱？',
            //             reverseButtons: true,
            //             confirmButtonText: '确定',
            //             preConfirm: () => {
            //                 // 请求后端接口
            //                 let formData = { email: this.bind_email };
            //                 return this.$api.auth.bindEmail(formData).then(response => {
            //                     this.$store.commit('certification/updateUser', response.data);
            //                 }).catch(() => {
            //                     return 'false';
            //                 });
            //             },
            //         }
            //     ]).then((result) => {
            //         // 点击取消按钮，直接返回
            //         if (result.dismiss) {
            //             return;
            //         }
            //
            //         if (result.value[2] !== 'false') {
            //
            //             // 刷新页面
            //             this.$router.push({name: 'setbindsns'});
            //
            //             this.$swal.fire({
            //                 type: 'success',
            //                 title: '绑定成功',
            //                 toast: true,
            //                 position: 'top',
            //                 showConfirmButton: false,
            //                 timer: 2000,
            //             }).then();
            //
            //         }else {
            //             this.$swal.fire({
            //                 type: 'error',
            //                 title: '哎呀，网络问题，绑定失败~',
            //                 toast: true,
            //                 position: 'top',
            //                 showConfirmButton: false,
            //                 timer: 3000,
            //             });
            //         }
            //     });
            // },

            // unbindEmail() {
            //     // 进行查库，看是不是有emial,如果没有email,不给解绑手机
            //     this.$api.auth.me().then(response => {
            //         if(response.data.phone) {
            //             this.$swal.mixin({
            //                 animation: false,
            //                 allowOutsideClick: false,
            //                 cancelButtonText: '取消',
            //                 confirmButtonText: '下一步',
            //                 showCancelButton: true,
            //                 progressSteps: ['1', '2', '3']
            //             }).queue([
            //                 {
            //                     text: this.$store.state.certification.email + ' 请输入登录密码验证身份',
            //                     input: 'text',
            //                     inputAutoTrim: true,
            //                     reverseButtons: true,
            //                     showLoaderOnConfirm: true,
            //                     inputValidator: (value) => {
            //                         return new Promise((resolve) => {
            //                             if (/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/.test(value)) {
            //                                 resolve()
            //                             } else {
            //                                 resolve('请输入正确的密码')
            //                             }
            //                         })
            //                     },
            //                     preConfirm: (value) => {
            //                         // 发送邮件验证码
            //                         let formData = { password: value };
            //                         return this.$api.auth.unbindEmailCode(formData).then(response => {
            //                             sessionStorage.setItem("verify_key", response.data.verify_key);
            //                         }).catch(error => {
            //                             this.$swal.showValidationMessage(error.response.data.errors.password);
            //                         })
            //                     }
            //                 },
            //                 {
            //                     text: '请输入验证码',
            //                     input: 'text',
            //                     inputAutoTrim: true,
            //                     reverseButtons: true,
            //                     inputValidator: (value) => {
            //                         return new Promise((resolve) => {
            //                             if (/^[0-9]{6}$/.test(value)) {
            //                                 resolve()
            //                             } else {
            //                                 resolve('请输入正确的验证码')
            //                             }
            //                         })
            //                     },
            //                     preConfirm: (value) => {
            //                         let formData = { email_code: value, verify_key: sessionStorage.getItem('verify_key') };
            //                         return this.$api.auth.checkEmailCode(formData).then(() => {}).catch(error => {
            //                             this.$swal.showValidationMessage(error.response.data.errors.code);
            //                         });
            //                     },
            //                 },
            //                 {
            //                     text: '确定解绑该邮箱 ' + this.$store.state.certification.email + ' ?',
            //                     reverseButtons: true,
            //                     confirmButtonText: '确定',
            //                     preConfirm: () => {
            //                         // 请求后端接口
            //                         return this.$api.auth.unbindEmail().then(response => {
            //                             this.$store.commit('certification/updateUser', response.data);
            //                         }).catch(() => {
            //                             return 'false';
            //                         });
            //                     },
            //                 }
            //             ]).then((result) => {
            //                 // 点击取消按钮，直接返回
            //                 if (result.dismiss) {
            //                     return;
            //                 }
            //
            //                 if (result.value[2] !== 'false') {
            //                     // 刷新页面
            //                     this.$router.push({name: 'setbindsns'});
            //
            //                     this.$swal.fire({
            //                         type: 'success',
            //                         title: '解绑成功',
            //                         toast: true,
            //                         position: 'top',
            //                         showConfirmButton: false,
            //                         timer: 2000,
            //                     }).then();
            //                 }else {
            //                     this.$swal.fire({
            //                         type: 'error',
            //                         title: '哎呀，网络问题，解绑失败~',
            //                         toast: true,
            //                         position: 'top',
            //                         showConfirmButton: false,
            //                         timer: 3000,
            //                     });
            //                 }
            //             });
            //
            //         }else {
            //             this.$swal.fire({
            //                 type: 'warning',
            //                 title: 'sorry， 您未绑定手机，无法解绑该邮箱~',
            //                 toast: true,
            //                 position: 'top',
            //                 showConfirmButton: false,
            //                 timer: 3000,
            //             })
            //         }
            //     });
            // }
        }
    }
</script>

<style scoped>
    .fa-envelope {
        float: left; margin-left: 20px; margin-right: 20px; font-size: 27px; color: #4c94a0
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

    .match {
        font-size: 17px;
    }

    .label {
        float: left; font-size: 15px; margin-right: 20px; margin-top: 6px; margin-left: 20px; color: #636b6f;
    }

    .form-control {
        width: 60%;
        margin-top: 10px;
    }

    .modal-body {
        padding-bottom: 0;
    }

    .light {
        box-shadow: none;
        color: #ffffff;
        background-color: #b4b4b4;
    }

    .light:hover {
        background-color: #aaaaaa;
    }

    .invalid-feedback, .password_message {
        margin-left: 161px;
    }

    .next {
        width: 70px;
        box-shadow: none;
        color: #ffffff;
        background-color: #4d94a0;
    }

    .next:hover {
        background-color: #50a3af;
    }

    .head {
        float: left;
        color: #545454;
    }

    .unbindEmail {
        font-size: 15px;
        color: #1b829d;
        margin-top: 6px;
        float: right;
        margin-right: 165px
    }

    .unbindEmails {
        font-size: 15px;
        color: #1b829d;
        margin-top: 6px;
        float: right;
        margin-right: 160px
    }
</style>