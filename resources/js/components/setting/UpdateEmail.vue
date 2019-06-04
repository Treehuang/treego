<template>
    <div>
        <div v-if="email">
            <i class="far fa-envelope"></i>
            <div class="email">
                <p class="info">
                    <span class="match">邮箱</span>
                    <span style="color: #1b829d">{{ email }}</span>
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
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'UpdateEmial',

        computed: {
            ...mapState({
                email : state => state.certification.email,
            })
        },

        data() {
            return {
                bind_email: null,
            }
        },

        methods: {

            bindEmail() {
                this.$swal.mixin({
                    animation: false,
                    allowOutsideClick: false,
                    cancelButtonText: '取消',
                    confirmButtonText: '下一步',
                    showCancelButton: true,
                    progressSteps: ['1', '2', '3']
                }).queue([
                    {
                        text: '请输入要绑定的邮箱',
                        input: 'text',
                        inputAutoTrim: true,
                        reverseButtons: true,
                        showLoaderOnConfirm: true,
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
                                    resolve()
                                } else {
                                    resolve('邮箱格式错误')
                                }
                            })
                        },
                        preConfirm: (value) => {
                            // 发送邮件验证码
                            let formData = { email: value };
                            return this.$api.auth.bindEmailCode(formData).then(response => {
                                this.bind_email = value;
                                sessionStorage.setItem("verify_key", response.data.verify_key);
                            }).catch(error => {
                                this.$swal.showValidationMessage(error.response.data.errors.password);
                            })
                        }
                    },
                    {
                        text: '请输入验证码',
                        input: 'text',
                        inputAutoTrim: true,
                        reverseButtons: true,
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (/^[0-9]{6}$/.test(value)) {
                                    resolve()
                                } else {
                                    resolve('请输入正确的验证码')
                                }
                            })
                        },
                        preConfirm: (value) => {
                            let formData = { email_code: value, verify_key: sessionStorage.getItem('verify_key') };
                            return this.$api.auth.checkEmailCode(formData).then(() => {}).catch(error => {
                                this.$swal.showValidationMessage(error.response.data.errors.code);
                            });
                        },
                    },
                    {
                        text: '确定绑定邮箱？',
                        reverseButtons: true,
                        confirmButtonText: '确定',
                        preConfirm: () => {
                            // 请求后端接口
                            let formData = { email: this.bind_email };
                            return this.$api.auth.bindEmail(formData).then(response => {
                                this.$store.commit('certification/updateUser', response.data);
                            }).catch(() => {
                                return 'false';
                            });
                        },
                    }
                ]).then((result) => {
                    // 点击取消按钮，直接返回
                    if (result.dismiss) {
                        return;
                    }

                    if (result.value[2] !== 'false') {
                        this.$swal.fire({
                            type: 'success',
                            text: '绑定成功!',
                            confirmButtonText: '确定'
                        }).then(() => {
                            // 绑定成功，不管点确定还是关闭，都刷新页面
                            this.$router.push({name: 'setbindsns'});
                        });
                    }else {
                        this.$swal.fire({
                            type: 'error',
                            text: '哎呀，网络问题，绑定失败~',
                            confirmButtonText: '确定'
                        });
                    }
                });
            },

            unbindEmail() {
                // 进行查库，看是不是有emial,如果没有email,不给解绑手机
                this.$api.auth.me().then(response => {
                    if(response.data.phone) {
                        this.$swal.mixin({
                            animation: false,
                            allowOutsideClick: false,
                            cancelButtonText: '取消',
                            confirmButtonText: '下一步',
                            showCancelButton: true,
                            progressSteps: ['1', '2', '3']
                        }).queue([
                            {
                                text: this.$store.state.certification.email + ' 请输入登录密码验证身份',
                                input: 'text',
                                inputAutoTrim: true,
                                reverseButtons: true,
                                showLoaderOnConfirm: true,
                                inputValidator: (value) => {
                                    return new Promise((resolve) => {
                                        if (/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,16}$/.test(value)) {
                                            resolve()
                                        } else {
                                            resolve('请输入正确的密码')
                                        }
                                    })
                                },
                                preConfirm: (value) => {
                                    // 发送邮件验证码
                                    let formData = { password: value };
                                    return this.$api.auth.unbindEmailCode(formData).then(response => {
                                        sessionStorage.setItem("verify_key", response.data.verify_key);
                                    }).catch(error => {
                                        this.$swal.showValidationMessage(error.response.data.errors.password);
                                    })
                                }
                            },
                            {
                                text: '请输入验证码',
                                input: 'text',
                                inputAutoTrim: true,
                                reverseButtons: true,
                                inputValidator: (value) => {
                                    return new Promise((resolve) => {
                                        if (/^[0-9]{6}$/.test(value)) {
                                            resolve()
                                        } else {
                                            resolve('请输入正确的验证码')
                                        }
                                    })
                                },
                                preConfirm: (value) => {
                                    let formData = { email_code: value, verify_key: sessionStorage.getItem('verify_key') };
                                    return this.$api.auth.checkEmailCode(formData).then(() => {}).catch(error => {
                                        this.$swal.showValidationMessage(error.response.data.errors.code);
                                    });
                                },
                            },
                            {
                                text: '确定解绑该邮箱 ' + this.$store.state.certification.email + ' ?',
                                reverseButtons: true,
                                confirmButtonText: '确定',
                                preConfirm: () => {
                                    // 请求后端接口
                                    return this.$api.auth.unbindEmail().then(response => {
                                        this.$store.commit('certification/updateUser', response.data);
                                    }).catch(() => {
                                        return 'false';
                                    });
                                },
                            }
                        ]).then((result) => {
                            // 点击取消按钮，直接返回
                            if (result.dismiss) {
                                return;
                            }

                            if (result.value[2] !== 'false') {
                                this.$swal.fire({
                                    type: 'success',
                                    text: '解绑成功！',
                                    confirmButtonText: '确定'
                                }).then(() => {
                                    // 解绑成功，不管点确定还是关闭，都刷新页面
                                    this.$router.push({name: 'setbindsns'});
                                });
                            }else {
                                this.$swal.fire({
                                    type: 'error',
                                    text: '哎呀，网络问题，解绑失败~',
                                    confirmButtonText: '确定'
                                });
                            }
                        });

                    }else {
                        this.$swal.fire({
                            type: 'warning',
                            text: 'sorry, 您未绑定手机,无法解绑该邮箱~',
                            confirmButtonText: '确定'
                        })
                    }
                });
            }
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
</style>