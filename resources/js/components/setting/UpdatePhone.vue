<template>
    <div>
        <div v-if="myPhone">
            <i class="fas fa-mobile-alt"></i>
            <div class="phone">
                <p class="info">
                    <span class="match">手机</span>
                    <span style="color: #1b829d">{{ myPhone }}</span>
                </p>
                <p class="prompt">可用手机号加密码登录 tree go，可通过手机号找回密码</p>
            </div>

            <button class="btn secondary" @click="unbindPhone">解绑</button>
        </div>

        <div v-else>
            <i class="fas fa-mobile-alt"></i>
            <div class="phone">
                <p class="info">
                    <span class="match">手机</span>
                    <span>您还未绑定，请点击绑定</span>
                </p>
                <p class="prompt">可用手机号加密码登录 tree go，可通过手机号找回密码</p>
            </div>

            <button class="btn secondary" @click="bindPhone">绑定</button>
        </div>

        <!--输入原绑定手机号-->
        <div class="modal" id="unbindPhone" tabindex="-1" role="dialog" aria-labelledby="unbindPhone" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">解绑手机号</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">原来已绑定的手机号</span>
                                <span class="unbindPhone">{{ myPhone }}</span>
                            </div>

                            <div class="clearfix"></div>

                            <div style="margin-top: 20px">
                                <label for="oldPhone" class="label">输入已绑定的手机号</label>
                                <input v-validate="'required|phone'" :class="[{'is-invalid' : errors.has('phone')}, 'form-control']" v-model="phone" name="phone" id="oldPhone" type="text">
                                <span class="invalid-feedback" v-if="errors.has('phone')">{{ errors.first('phone') }}</span>
                                <span class="message phone_message" v-if="!errors.has('phone')">{{ phone_message }}</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button class="btn next" @click="nextOne">下一步</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--输入验证码-->
        <div class="modal" id="unbindPhoneSmsCode" tabindex="-1" role="dialog" aria-labelledby="unbindPhoneSms" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">解绑手机号</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">
                            <div>
                                <span class="label">原来已绑定的手机号</span>
                                <span class="unbindPhones">{{ phone }}</span>
                            </div>

                            <div class="clearfix"></div>

                            <div style="margin-top: 20px">
                                <label for="smscode" class="label">请输入收到的验证码</label>
                                <input v-validate="'required|smscode'" :class="[{'is-invalid' : errors.has('smscode')}, 'form-control']" v-model="smscode" name="smscode" id="smscode" type="text">
                                <span class="invalid-feedback" v-if="errors.has('smscode')">{{ errors.first('smscode') }}</span>
                                <span class="message phone_message" v-if="!errors.has('smscode')">{{ smscode_message }}</span>
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

        <!--确认是否解绑该手机号-->
        <div class="modal" id="sureUnbindPhone" tabindex="-1" role="dialog" aria-labelledby="sureUnbindPhone" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">解绑手机号</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">确定解绑该手机号</span>
                                <span class="sureUnbindPhone">{{ phone }} ？</span>
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


        <!--输入要绑定的手机号-->
        <div class="modal" id="bindPhone" tabindex="-1" role="dialog" aria-labelledby="bindPhone" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">绑定手机号</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">绑定后，您可以使用此手机号进行找回密码及登录的相关操作</span>
                            </div>

                            <div class="clearfix"></div>

                            <div style="margin-top: 20px">
                                <label for="bindphones" class="label">输入要绑定的手机号</label>
                                <input v-validate="'required|bindphone'" :class="[{'is-invalid' : errors.has('bindphone')}, 'form-control']" v-model="bindphone" name="bindphone" id="bindphones" type="text">
                                <span class="invalid-feedback" v-if="errors.has('bindphone')">{{ errors.first('bindphone') }}</span>
                                <span class="message phone_message" v-if="!errors.has('bindphone')">{{ bindphone_message }}</span>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button class="btn next" @click="bindNextOne">下一步</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!--输入验证码-->
        <div class="modal" id="bindPhoneSmsCode" tabindex="-1" role="dialog" aria-labelledby="bindPhoneSms" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">绑定手机号</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">
                            <div>
                                <span class="label">绑定后，您可以使用此手机号进行找回密码及登录的相关操作</span>
                            </div>

                            <div class="clearfix"></div>

                            <div style="margin-top: 20px">
                                <label for="bindsmscode" class="label">请输入收到的验证码</label>
                                <input v-validate="'required|bindsmscode'" :class="[{'is-invalid' : errors.has('bindsmscode')}, 'form-control']" v-model="bindsmscode" name="bindsmscode" id="bindsmscode" type="text">
                                <span class="invalid-feedback" v-if="errors.has('bindsmscode')">{{ errors.first('bindsmscode') }}</span>
                                <span class="message phone_message" v-if="!errors.has('bindsmscode')">{{ bindsmscode_message }}</span>
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

        <!--确认是否绑定该手机号-->
        <div class="modal" id="sureBindPhone" tabindex="-1" role="dialog" aria-labelledby="sureBindPhone" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="head">绑定手机号</h5>

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">

                            <div>
                                <span class="label">确认绑定该手机号</span>
                                <span class="sureUnbindPhone">{{ bindphone }} ？</span>
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
        name: 'UpdatePhone',

        computed: {
            ...mapState({
                myPhone : state => state.certification.phone,
            })
        },

        watch: {
            phone() {
                this.phone_message = '';
            },

            smscode() {
                this.smscode_message = '';
            },

            bindphone() {
                this.bindphone_message = '';
            },

            bindsmscode() {
                this.bindsmscode_message = '';
            }
        },

        data() {
            return {
                phone: null,
                smscode: null,
                bindphone: null,
                bindsmscode: null,
                phone_message: null,
                bindphone_message: null,
                smscode_message: null,
                bindsmscode_message: null,
            }
        },

        methods: {

            unbindPhone() {
                // 进行查库，看是不是有emial,如果没有email,不给解绑手机
                this.$api.auth.me().then(response => {
                    if (response.data.email) {
                        $('#unbindPhone').modal('show');
                    }else {
                        this.$swal.fire({
                            type: 'warning',
                            title: 'sorry， 您未绑定邮箱，无法解绑该手机号~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000,
                        })
                    }
                }).catch();
            },

            nextOne() {
                this.$validator.validate('phone').then((result) => {
                    if (result) {
                        let formData = { phone: this.phone };
                        this.$api.auth.unbindPhoneSms(formData).then(response => {
                            sessionStorage.setItem("verify_key", response.data.verify_key);
                            $('#unbindPhone').modal('hide');
                            $('#unbindPhoneSmsCode').modal('show');
                        }).catch(error => {
                            this.phone_message = error.response.data.errors.phone;
                        })
                    }
                })
            },

            nextTwo() {
                this.$validator.validate('smscode').then((result) => {
                    if (result) {
                        let formData = {smscode: this.smscode, verify_key: sessionStorage.getItem('verify_key')};
                        this.$api.auth.checkSmsWithUpdate(formData).then(() => {
                            $('#unbindPhoneSmsCode').modal('hide');
                            $('#sureUnbindPhone').modal('show');
                        }).catch(error => {
                            this.smscode_message = error.response.data.errors.smscode;
                        })
                    }
                })
            },

            sureUnbind() {
                this.$api.auth.unbindPhone().then(response => {
                    this.$store.commit('certification/updateUser', response.data);
                    $('#sureUnbindPhone').modal('hide');
                    this.$swal.fire({
                        type: 'success',
                        title: '解绑成功',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                    }).then();
                }).catch(() => {
                    $('#sureUnbindPhone').modal('hide');
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

            bindPhone() {
                $('#bindPhone').modal('show');
            },

            bindNextOne() {
                this.$validator.validate('bindphone').then((result) => {
                    if (result) {
                        let formData = { phone: this.bindphone };
                        this.$api.auth.bindPhoneSms(formData).then(response => {
                            sessionStorage.setItem("verify_key", response.data.verify_key);
                            $('#bindPhone').modal('hide');
                            $('#bindPhoneSmsCode').modal('show');
                        }).catch(error => {
                            this.bindphone_message = error.response.data.errors.phone;
                        })
                    }
                })
            },

            bindNextTwo() {
                this.$validator.validate('bindsmscode').then((result) => {
                    if (result) {
                        let formData = {smscode: this.bindsmscode, verify_key: sessionStorage.getItem('verify_key')};
                        this.$api.auth.checkSmsWithUpdate(formData).then(() => {
                            $('#bindPhoneSmsCode').modal('hide');
                            $('#sureBindPhone').modal('show');
                        }).catch(error => {
                            this.bindsmscode_message = error.response.data.errors.smscode;
                        })
                    }
                })
            },

            sureBind() {
                let formData = { phone: this.bindphone };
                this.$api.auth.bindPhone(formData).then(response => {
                    this.$store.commit('certification/updateUser', response.data);
                    $('#sureBindPhone').modal('hide');
                    this.$swal.fire({
                        type: 'success',
                        title: '绑定成功',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2000,
                    }).then();
                }).catch(() => {
                    $('#sureBindPhone').modal('hide');
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

            // unbindPhone() {
            //     // 进行查库，看是不是有emial,如果没有email,不给解绑手机
            //     this.$api.auth.me().then(response => {
            //         if(response.data.email) {
            //             this.$swal.mixin({
            //                 animation: false,
            //                 allowOutsideClick: false,
            //                 cancelButtonText: '取消',
            //                 confirmButtonText: '下一步',
            //                 showCancelButton: true,
            //                 progressSteps: ['1', '2', '3']
            //             }).queue([
            //                 {
            //                     text: '请输入已绑定的手机号',
            //                     input: 'text',
            //                     inputAutoTrim: true,
            //                     reverseButtons: true,
            //                     showLoaderOnConfirm: true,
            //                     inputValidator: (value) => {
            //                         return new Promise((resolve) => {
            //                             if (/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/.test(value)) {
            //                                 resolve()
            //                             } else {
            //                                 resolve('请输入正确的手机号')
            //                             }
            //                         })
            //                     },
            //                     preConfirm: (value) => {
            //                         // 发送短信验证码
            //                         let formData = { phone: value };
            //                         return this.$api.auth.unbindPhoneSms(formData).then(response => {
            //                             sessionStorage.setItem("verify_key", response.data.verify_key);
            //                         }).catch(error => {
            //                             this.$swal.showValidationMessage(error.response.data.errors.phone);
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
            //                         let formData = { smscode: value, verify_key: sessionStorage.getItem('verify_key') };
            //                         return this.$api.auth.checkSmsWithUpdate(formData).then(() => {}).catch(error => {
            //                             this.$swal.showValidationMessage(error.response.data.errors.smscode);
            //                         });
            //                     },
            //                 },
            //                 {
            //                     html: '<p>确定解绑该手机号？</p> ' + this.$store.state.certification.phone,
            //                     reverseButtons: true,
            //                     confirmButtonText: '确定',
            //                     preConfirm: () => {
            //                         // 请求后端接口
            //                         return this.$api.auth.unbindPhone().then(response => {
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
            //
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
            //             })
            //         }else {
            //             this.$swal.fire({
            //                 type: 'warning',
            //                 title: 'sorry， 您未绑定邮箱，无法解绑该手机号~',
            //                 toast: true,
            //                 position: 'top',
            //                 showConfirmButton: false,
            //                 timer: 3000,
            //             })
            //         }
            //     });
            // },

            // bindPhone() {
            //     this.$swal.mixin({
            //         animation: false,
            //         allowOutsideClick: false,
            //         cancelButtonText: '取消',
            //         confirmButtonText: '下一步',
            //         showCancelButton: true,
            //         progressSteps: ['1', '2', '3']
            //     }).queue([
            //         {
            //             text: '请输入要绑定的手机号',
            //             input: 'text',
            //             inputAutoTrim: true,
            //             reverseButtons: true,
            //             showLoaderOnConfirm: true,
            //             inputValidator: (value) => {
            //                 return new Promise((resolve) => {
            //                     if (/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/.test(value)) {
            //                         resolve()
            //                     } else {
            //                         resolve('手机号格式错误')
            //                     }
            //                 })
            //             },
            //             preConfirm: (value) => {
            //                 // 发送短信验证码
            //                 let formData = { phone: value };
            //                 return this.$api.auth.bindPhoneSms(formData).then(response => {
            //                     sessionStorage.setItem("bind_unbind_phone", value);
            //                     sessionStorage.setItem("verify_key", response.data.verify_key);
            //                 }).catch(error => {
            //                     this.$swal.showValidationMessage(error.response.data.errors.phone);
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
            //                 let formData = { smscode: value, verify_key: sessionStorage.getItem('verify_key') };
            //                 return this.$api.auth.checkSmsWithUpdate(formData).then(() => {}).catch(error => {
            //                     this.$swal.showValidationMessage(error.response.data.errors.smscode);
            //                 });
            //             },
            //         },
            //         {
            //             text: '是否要绑定该手机号 ' + sessionStorage.getItem("bind_unbind_phone") + ' ?',
            //             reverseButtons: true,
            //             confirmButtonText: '确定',
            //             preConfirm: () => {
            //                 // 请求后端接口
            //                 let formData = {phone: sessionStorage.getItem("bind_unbind_phone")};
            //                 return this.$api.auth.bindPhone(formData).then(response => {
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
            //             // 绑定成功,刷新页面
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
            //     })
            // }
        }
    }
</script>

<style scoped>
    .fa-mobile-alt {
        float: left; margin-left: 25px; margin-right: 24px; font-size: 27px; color: #4c94a0
    }

    .phone {
        float: left; margin-right: 260px;
    }

    .match {
        font-size: 17px;
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

    .label {
        float: left; font-size: 15px; margin-right: 20px; margin-top: 6px; margin-left: 20px; color: #636b6f;
    }

    .form-control {
        width: 58%;
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

    .invalid-feedback, .phone_message {
        margin-left: 175px;
    }

    .next {
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

    .unbindPhone {
        font-size: 15px;
        color: #1b829d;
        margin-top: 6px;
        float: right;
        margin-right: 214px
    }

    .unbindPhones {
        font-size: 15px;
        color: #1b829d;
        margin-top: 6px;
        float: right;
        margin-right: 200px
    }

    .sureUnbindPhone {
        font-size: 15px;
        color: #1b829d;
        margin-top: 6px;
        float: right;
        margin-right: 190px
    }
</style>