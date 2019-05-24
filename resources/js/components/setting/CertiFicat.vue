<template>
    <div class="container">
        <side-bar></side-bar>

        <div class="card" style="float: left;">
            <div class="card-body">
                <h5 class="card-title">学籍认证</h5>

                <hr>

                <form @submit.prevent="audit">
                    <div>
                        <label class="username" for="username">姓名</label>
                        <input type="text" class="form-control" name="username" id="username" v-model="username">
                    </div>

                    <hr>

                    <div>
                        <label class="school" for="school">院校</label>
                        <input type="text" class="form-control" name="school" id="school" v-model="school" disabled>
                    </div>

                    <hr>

                    <div>
                        <label class="record" for="record">学历</label>
                        <select class="form-control" name="record" id="record" v-model="record">
                            <option value="u">本科生</option>
                            <option value="r">研究生</option>
                        </select>
                    </div>

                    <hr>

                    <div>
                        <label class="time" for="time">入学时间</label>
                        <select class="form-control" name="time" id="time" v-model="time">
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                        </select>
                    </div>

                    <hr>

                    <div>
                        <label class="prove" for="prove">学籍证明</label>
                        <input type="text" class="form-control" name="prove" id="prove">
                    </div>

                    <hr>

                    <button type="submit" class="btn secondary">提交审核</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div style="width: 240px; margin-top: 85px; float: right;">
                <h6 class="card-title" style="color: #ff9600;">认证须知</h6>
                <div style="color:#45555d;">
                    <p>
                        • 学籍证明中的不能有作假信息，如有误，将无法通过审核；
                    </p>

                    <p>
                        • 你提交学籍证明后的1个工作日内完成审核，审核结果将会以系统通知的形式发送给你；
                    </p>

                    <p>
                        • 上传学籍证明文件格式为PDF或JPG格式。请确保学籍文件清晰可见；
                    </p>

                    <p>
                        • 我们会确保你所提供的信息均处于严格的保密状态，不会泄露；
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SideBar from './SideBar';

    export default {
        components: {
            SideBar
        },

        data() {
            return {
                time: '2014',
                record: 'u',
                school: '广州大学',
                username: '',
            }
        },

        methods: {
            audit() {
                console.log(this.time)
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
        width: 600px;
    }

    .form-control {
        width: 198px; box-shadow: none
    }

    .username, .school, .record {
        float: left;
        color: #9ca0a4;
        margin-top: 8px;
        margin-left: 2px;
        margin-right: 150px;
        font-size: 15px
    }

    .time, .prove {
        float: left;
        color: #9ca0a4;
        margin-top: 8px;
        margin-left: 2px;
        margin-right: 120px;
        font-size: 15px;
    }

    .secondary {
        width: 90px; font-size: 15px; border-radius: 20px; margin-top: 8px; margin-bottom: 8px; color: #6c757d; border-color: #6c757d; box-shadow: none;float: right; margin-right: 50px;
    }

    .secondary:hover {
        color: #5ca9b7; border-color: #5da9b6; background-color: rgba(200, 255, 193, 0);
    }
</style>