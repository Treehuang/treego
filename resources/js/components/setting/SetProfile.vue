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

                    <div>
                        <label class="username" for="username">昵称</label>
                        <input type="text" class="form-control" name="username" id="username" v-model="username">
                    </div>

                    <hr>

                    <div>
                        <label class="collage" for="college">学院</label>
                        <input type="text" class="form-control" name="college" id="college" v-model="collage">
                    </div>

                    <hr>

                    <div>
                        <label class="campus" for="campus">校区</label>
                        <input type="text" class="form-control" name="campus" id="campus" v-model="campus">
                    </div>

                    <hr>

                    <div>
                        <label class="introduction" for="introduction">签名</label>
                        <textarea name="introduction" id="introduction" class="form-control" rows="3">{{ introduction }}</textarea>
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

        data() {
            return {
                sex: 'm',
                campus: '大学城',
                collage: '机械与电气工程学院',
                username: '14-电气-黄树斌',
                introduction: '勤学如春起之苗，不见其增，日有所长！',
            }
        },

        methods: {
            save() {
                console.log(this.sex);
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
        width: 198px; box-shadow: none
    }

    .secondary {
        width: 70px; font-size: 15px; border-radius: 20px; margin-top: 8px; margin-bottom: 8px; color: #6c757d; border-color: #6c757d; box-shadow: none;float: right; margin-right: 50px;
    }

    .secondary:hover {
        color: #5ca9b7; border-color: #5da9b6; background-color: rgba(200, 255, 193, 0);
    }
</style>