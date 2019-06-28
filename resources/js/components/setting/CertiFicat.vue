<template>
    <div class="container">
        <side-bar></side-bar>

        <div class="card" style="float: left;">
            <div class="card-body">
                <h5 class="card-title">学籍认证
                    <span v-if="is_check === 1" style="color: #4b8f9b;">（已认证）</span>
                    <span v-if="is_check === 2" style="color: #4b8f9b;">（审核中）</span>
                    <span v-if="is_check === 3" style="color: #d36b75;">（审核不通过）</span>
                </h5>

                <hr>

                <div class="itembox">
                    <label class="realname" for="realname">姓名</label>
                    <input v-validate="'required|realname'" type="text" :class="[{ 'is-invalid' : errors.has('realname') }, 'form-control']" id="realname" name="realname" v-model="realname" placeholder="请输入真实姓名">
                    <span  class="invalid-feedback error-message" v-if="errors.has('realname')">{{ errors.first('realname') }}</span>
                    <span class="message error-message" v-if="!errors.has('realname')">{{ realerror }}</span>
                </div>

                <hr>

                <div>
                    <label class="school" for="school">院校</label>
                    <input type="text" class="form-control" name="school" id="school" v-model="school" disabled>
                </div>

                <hr>

                <div>
                    <label class="record" for="record">学历</label>
                    <el-select name="record" id="record" v-model="record">
                        <el-option key="1" label="本科生" value="u"></el-option>
                        <el-option key="2" label="研究生" value="r"></el-option>
                    </el-select>
                </div>

                <hr>

                <div>
                    <label class="time" for="time">入学时间</label>
                    <el-select name="time" id="time" v-model="time">
                        <el-option key="1" label="2014" value="2014"></el-option>
                        <el-option key="2" label="2015" value="2015"></el-option>
                        <el-option key="3" label="2016" value="2016"></el-option>
                        <el-option key="4" label="2017" value="2017"></el-option>
                        <el-option key="5" label="2018" value="2018"></el-option>
                        <el-option key="6" label="2019" value="2019"></el-option>
                    </el-select>
                </div>

                <hr>

                <div class="itembox">

                    <label class="prove" for="prove">学籍证明</label>
                    <button  :class="style" style="float: left; position: relative">{{ message }}
                        <input type="file" @change="uploadAvatar($event)" accept="image/png,image/jpeg,image/jpg" class="file" id="prove" title="">
                    </button>
                    <div class="clearfix"></div>
                    <span class="message upload" v-if="noUpload">请上传学籍证明</span>
                    <span class="message upload" v-if="show_certificat_error">{{ certificat_error }}</span>

                    <span class="error-message hand" @click="hand">什么可以是学籍证明文件？</span>
                </div>

                <hr>

                <button type="submit" class="btn secondary"  @click="audit">提交审核</button>

            </div>
        </div>

        <div class="card">
            <div style="width: 219px; margin-top: 85px; float: right;">
                <h6 class="card-title" style="color: #ff9600;">认证须知</h6>
                <div style="color:#45555d;">
                    <p>
                        • 上传学籍证明文件格式为PNG或JPG格式。请确保学籍文件清晰可见；
                    </p>

                    <p>
                        • 你提交学籍证明后的1个工作日内完成审核，审核结果将会以系统通知的形式发送给你；
                    </p>

                    <p>
                        • 学籍证明中的不能有作假信息，如有误，将无法通过审核；
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

        created() {
            this.$api.auth.getIsCheck().then(response => {
                this.is_check = response.data.is_check;
            })
        },

        data() {
            return {
                file: null,
                oldFile: null,
                time: '2014',
                style: 'btn primary',
                record: 'u',
                is_check: false,
                noUpload: false,
                school: '广州大学',
                message: '上传文件',
                realname: '',
                realerror: '',
                certificat_error: '',
                show_certificat_error: false,
            }
        },

        watch: {
            realname() {
                this.nameerror = '';
            }
        },

        methods: {

            audit() {
                // 如果是已经认证成功的，直接返回
                if (this.is_check === 1) {
                    this.$swal.fire({
                        type: 'success',
                        title: '您已认证，无需进行此操作~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then()
                    return false;
                }

                if (this.is_check === 2) {
                    this.$swal.fire({
                        type: 'warning',
                        title: '资料审核中，无需进行此操作~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then()
                    return false;
                }

                // 判断是否上传了图像
                if (!this.oldFile) {
                    this.noUpload = true;
                }

                this.$validator.validateAll().then((result) => {
                    if (result && !this.noUpload) {
                        // 后端对接
                        let formData = new FormData();
                        formData.set('certificat', this.oldFile);
                        formData.set('realname', this.realname);
                        formData.set('record', this.record);
                        formData.set('time', this.time);

                        this.$api.auth.uploadCertificat(formData).then(() => {
                            this.$swal.fire({
                                type: 'success',
                                title: '提交成功！',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 2500,
                            }).then()
                            this.is_check = 2;
                        }).catch(error => {
                            if (error.data.errors.certificat) {
                                this.show_certificat_error = true;
                                this.certificat_error = error.data.errors.certificat;
                            }
                        })
                    }
                });
            },

            uploadAvatar(e) {
                // 将上传图片不合格的错误消除
                this.show_certificat_error = false;

                // 判断浏览器是否支持FileReader
                if (!window.FileReader) {
                    this.$swal.fire({
                        type: 'warning',
                        title: '很抱歉，您的浏览器不支持图片上传功能',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                    }).then();
                }

                // 获取图片对象
                let files = e.target.files || e.dataTransfer.files;
                this.file = files[0];

                // 上传图像，再次点击上传图像（没有上传）， 点击取消
                if (!this.file) {
                    return;
                }

                // 判断上传的是不是image
                if (!/^image/.test(this.file.type)) {

                    // 清除路径,使得上传同一文件可以触发change
                    e.srcElement.value = "" ;

                    this.noUpload = true;
                    this.style = 'btn danger';
                    this.message = '上传失败';

                    this.$swal.fire({
                        type: 'warning',
                        toast: true,
                        position: 'top',
                        title: '请上传格式为png，jpeg，jpg的图片',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();

                    return false;
                }

                // 判断图片是否超过5m
                if (this.file.size / 1024 > 2049) {

                    // 清除路径,使得上传同一文件可以触发change
                    e.srcElement.value = "" ;

                    this.noUpload = true;
                    this.style = 'btn danger';
                    this.message = '上传失败';

                    this.$swal.fire({
                        type: 'warning',
                        title: '请上传大小不超过2M的图片',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();

                    return false;
                }

                this.noUpload = false;
                this.oldFile = this.file;
                this.style = 'btn success';
                this.message = '上传成功';
            },

            hand() {
                this.$swal.fire({
                   html:
                    '<div style="color:#45555d;">' +
                        '<p style="float: left">' +
                            '• 您的一卡通证件；' +
                        '</p>' +
                        '<div class="clearfix"></div>' +
                        '<p style="float: left">' +
                            '• 同乡会负责人的签名认证；' +
                        '</p>' +
                        '<div class="clearfix"></div>' +
                        '<p style="float: left">' +
                            '• 您的录取通知书，不必提供身份证信息；' +
                        '</p>' +
                        '<div class="clearfix"></div>' +
                    '</div>',
                    // confirmButtonText: '确认',
                    showConfirmButton: false,
                }).then();
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

    .realname, .school, .record {
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

    .file {
        display: inline-block;
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
        width: 198px;
        height: 35px;
        font-size: 0;
    }

    .primary {
        width: 198px;
        color: #ffffff;
        background-color: #51a1ad;
    }

    .primary:hover {
        background-color: #55a8b4;
    }

    .success {
        width: 198px;
        color: #ffffff;
        background-color: #37b96b;
    }

    .success:hover {
        background-color: #35af63;
    }

    .danger {
        width: 198px;
        color: #ffffff;
        background-color: #ee6763;
    }

    .danger:hover {
        background-color: #ff6f6b;
    }

    .itembox {
        position: relative;
    }

    .error-message {
        position: absolute; top: 6px; left: 390px;
    }

    .hand {
        color:#45555d;
        cursor: pointer;
    }

    .hand:hover {
        color: #1b1e21;
    }

    .error-message {
        width: 200px;
        position: absolute; top: 10px; left: 385px;
    }

    .upload {
        margin-left: 182px;
    }

    /deep/ .el-select {
        width: 198px;
    }

    /deep/ .el-select .el-input.is-focus .el-input__inner {
        border: 1px solid #4b8f9b !important;
    }

    .el-select-dropdown__item.selected {
        color: #4b8f9b;
        font-weight: normal;
    }
</style>