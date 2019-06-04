<template>
    <div>
        <div v-if="phone">
            <i class="fas fa-mobile-alt"></i>
            <div class="phone">
                <p class="info">
                    <span class="match">手机</span>
                    <span style="color: #1b829d">{{ phone }}</span>
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
    </div>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'UpdatePhone',

        computed: {
            ...mapState({
                phone : state => state.certification.phone,
            })
        },

        methods: {
            unbindPhone() {
                // 进行查库，看是不是有emial,如果没有email,不给解绑手机
                this.$api.auth.me().then(response => {
                    if(response.data.email) {
                        this.$swal.mixin({
                            animation: false,
                            allowOutsideClick: false,
                            cancelButtonText: '取消',
                            confirmButtonText: '下一步',
                            showCancelButton: true,
                            progressSteps: ['1', '2', '3']
                        }).queue([
                            {
                                text: '请输入已绑定的手机号',
                                input: 'text',
                                inputAutoTrim: true,
                                reverseButtons: true,
                                showLoaderOnConfirm: true,
                                inputValidator: (value) => {
                                    return new Promise((resolve) => {
                                        if (/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/.test(value)) {
                                            resolve()
                                        } else {
                                            resolve('请输入正确的手机号')
                                        }
                                    })
                                },
                                preConfirm: (value) => {
                                    // 发送短信验证码
                                    let formData = { phone: value };
                                    return this.$api.auth.unbindPhoneSms(formData).then(response => {
                                        sessionStorage.setItem("verify_key", response.data.verify_key);
                                    }).catch(error => {
                                        this.$swal.showValidationMessage(error.response.data.errors.phone);
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
                                    let formData = { smscode: value, verify_key: sessionStorage.getItem('verify_key') };
                                    return this.$api.auth.checkSmsWithUpdate(formData).then(() => {}).catch(error => {
                                        this.$swal.showValidationMessage(error.response.data.errors.smscode);
                                    });
                                },
                            },
                            {
                                html: '<p>确定解绑该手机号？</p> ' + this.$store.state.certification.phone,
                                reverseButtons: true,
                                confirmButtonText: '确定',
                                preConfirm: () => {
                                    // 请求后端接口
                                    return this.$api.auth.unbindPhone().then(response => {
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
                        })
                    }else {
                        this.$swal.fire({
                            type: 'warning',
                            text: 'sorry, 您未绑定邮箱,无法解绑该手机号~',
                            confirmButtonText: '确定'
                        })
                    }
                });
            },

            bindPhone() {
                this.$swal.mixin({
                    animation: false,
                    allowOutsideClick: false,
                    cancelButtonText: '取消',
                    confirmButtonText: '下一步',
                    showCancelButton: true,
                    progressSteps: ['1', '2', '3']
                }).queue([
                    {
                        text: '请输入要绑定的手机号',
                        input: 'text',
                        inputAutoTrim: true,
                        reverseButtons: true,
                        showLoaderOnConfirm: true,
                        inputValidator: (value) => {
                            return new Promise((resolve) => {
                                if (/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/.test(value)) {
                                    resolve()
                                } else {
                                    resolve('手机号格式错误')
                                }
                            })
                        },
                        preConfirm: (value) => {
                            // 发送短信验证码
                            let formData = { phone: value };
                            return this.$api.auth.bindPhoneSms(formData).then(response => {
                                sessionStorage.setItem("bind_unbind_phone", value);
                                sessionStorage.setItem("verify_key", response.data.verify_key);
                            }).catch(error => {
                                this.$swal.showValidationMessage(error.response.data.errors.phone);
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
                            let formData = { smscode: value, verify_key: sessionStorage.getItem('verify_key') };
                            return this.$api.auth.checkSmsWithUpdate(formData).then(() => {}).catch(error => {
                                this.$swal.showValidationMessage(error.response.data.errors.smscode);
                            });
                        },
                    },
                    {
                        text: '是否要绑定该手机号 ' + sessionStorage.getItem("bind_unbind_phone") + ' ?',
                        reverseButtons: true,
                        confirmButtonText: '确定',
                        preConfirm: () => {
                            // 请求后端接口
                            let formData = {phone: sessionStorage.getItem("bind_unbind_phone")};
                            return this.$api.auth.bindPhone(formData).then(response => {
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
                })
            }
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
</style>