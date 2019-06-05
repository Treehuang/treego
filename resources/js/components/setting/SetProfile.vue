<template>
    <div class="container">

        <side-bar></side-bar>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">个人信息</h5>

                <hr>

                <form @submit.prevent="save">
                    <div>
                        <div class="sex">
                            <span>性别</span>
                        </div>

                        <div class="form-check form-check-inline" style="margin-right: 40px">
                            <input class="form-check-input" type="radio" name="sex" id="1" value="m" v-model="sex">
                            <label class="form-check-label" for="1">男</label>
                        </div>
                        <div class="form-check form-check-inline" style="margin-right: 40px">
                            <input class="form-check-input" type="radio" name="sex" id="2" value="f" v-model="sex">
                            <label class="form-check-label" for="2">女</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="sex" id="3" value="s" v-model="sex">
                            <label class="form-check-label" for="3">保密</label>
                        </div>
                    </div>

                    <hr>

                    <div class="itembox">
                        <label class="username" for="username">昵称</label>
                        <input v-validate="'required|nickname'" type="text" :class="[{ 'is-invalid' : errors.has('nickname') }, 'form-control']" name="nickname" id="username" v-model="nickname">
                        <span  class="invalid-feedback error-message" v-if="errors.has('nickname')">{{ errors.first('nickname') }}</span>
                        <span class="message error-message" v-if="!errors.has('nickname')">{{ usererror }}</span>
                    </div>

                    <hr>

                    <div class="itembox">
                        <label class="collage" for="college">学院</label>
                        <input v-validate="'college'" type="text" :class="[{ 'is-invalid' : errors.has('college') }, 'form-control']" name="college" id="college" v-model="college">
                        <span  class="invalid-feedback error-message" v-if="errors.has('college')">{{ errors.first('college') }}</span>
                    </div>

                    <hr>

                    <div>
                        <label class="campus" for="campus">校区</label>
                        <select class="form-control" name="campus" id="campus" v-model="campus">
                            <option value="b">大学城</option>
                            <option value="g">桂花岗</option>
                        </select>
                    </div>

                    <hr>

                    <div class="itembox">
                        <label class="introduction" for="introduction">签名</label>
                        <textarea v-validate="'introduction'" :class="[{ 'is-invalid' : errors.has('introduction') }, 'form-control']" name="introduction" id="introduction" rows="3" v-model="introduction"></textarea>
                        <span class="invalid-feedback error-message" v-if="errors.has('introduction')">{{ errors.first('introduction') }}</span>
                    </div>

                    <hr>

                    <button type="submit" class="btn secondary">保存</button>
                </form>

            </div>
        </div>

    </div>
</template>

<script>
    import SideBar from './SideBar';

    export default {
        components: {SideBar},

        created() {
            // 向后端请求数据
            this.$api.auth.me().then(response => {
                this.sex = response.data.sex;
                this.campus = response.data.campus;
                this.college = response.data.college;
                this.nickname = response.data.username;
                this.introduction = response.data.introduction;
            })
        },

        watch: {
            nickname() {
                this.usererror = '';
            }
        },

        data() {
            return {
                sex: '',
                campus: '',
                college: '',
                nickname: '',
                usererror: '',
                introduction: '',
            }
        },

        methods: {
            save() {
                this.$validator.validateAll().then((result) => {
                    if(result) {

                        let formData = {
                            sex: this.sex,
                            campus: this.campus,
                            college: this.college,
                            nickname: this.nickname,
                            introduction: this.introduction,
                        };

                        this.$store.dispatch('certification/updateUser', formData).then(() => {
                            this.$swal.fire({
                                type: 'success',
                                title: '修改成功',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 2000,
                            }).then(() => {
                                this.$router.push({name: 'setprofile'});
                            })
                        }).catch(error => {
                            if(error.response.data.errors.nickname) {
                                this.usererror = error.response.data.errors.nickname;
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

    .card {
        border: none;
        background-color: #f8fafc
    }

    .card-body {
        width: 755px;
    }

    .sex {
        float: left; color: #9ca0a4; margin-left: 2px; margin-right: 150px; font-size: 15px
    }

    .username, .collage, .campus, .introduction {
        float: left;
        color: #9ca0a4;
        margin-top: 8px;
        margin-left: 2px;
        margin-right: 150px;
        font-size: 15px
    }

    .form-control {
        width: 198px; box-shadow: none;
    }

    .secondary {
        width: 70px; font-size: 15px; border-radius: 20px; margin-top: 8px; margin-bottom: 8px; color: #6c757d; border-color: #6c757d; box-shadow: none;float: right; margin-right: 50px;
    }

    .secondary:hover {
        color: #5ca9b7; border-color: #5da9b6; background-color: rgba(200, 255, 193, 0);
    }

    .itembox {
        position: relative;
    }

    .error-message {
        position: absolute; top: 6px; left: 390px;
    }
</style>