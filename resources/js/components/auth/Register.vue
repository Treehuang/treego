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

        <div class="row">
            <div class="col-md-12 text-center"><p><small>&copy; Inspiration comes from life!</small></p></div>
        </div>
    </div>

</template>

<script>

    export default {
        data() {
            return {
                phone: ''
            }
        },

        methods: {
            register() {
                let formData = {
                    phone: this.phone
                };

                this.$validator.validateAll().then((result) => {
                    if(result) {

                        // to do
                        console.log(formData)
                    }
                })
            }
        }
    }
</script>

<style scoped>
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
</style>