<template>
    <div class="avator">
        <img :src="avatar" class="rounded-circle" width="100px" height="100px">
        <div class="update">
            <span style="color: #ffffff; font-size: 12px;" data-toggle="modal" data-target="#updateAvatar">更 换 头 像</span>
        </div>

        <!--模态框-->
        <div class="modal" id="updateAvatar" tabindex="-1" role="dialog" aria-labelledby="updateAvatar" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 style="float: left; color: #545454">修改头像</h5>

                        <div class="clearfix"></div>

                        <img :src="uavatar" class="rounded-circle" width="200px" height="200px" style="margin-top: 10px; margin-bottom: 20px">

                        <div class="clearfix"></div>

                        <div style="margin-bottom: 20px">
                            <span class="updateAvatar" style="margin-right: 30px" @click="getAvatar">换一换</span>
                            <span  class="updateAvatar" style="position: relative;">上传头像
                                <input type="file" @change="uploadAvatar($event)" accept="image/png,image/jpeg,image/jpg" class="file" title="">
                            </span>
                        </div>

                        <div class="modal-footer">
                            <button class="btn light" @click="close">取消</button>
                            <button type="submit" class="btn primary" @click="updateAvatar">确定</button>
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
        name: 'UpdateAvatar',

        computed: {
            ...mapState({
                avatar: state => state.certification.avatar,
            })
        },

        created() {
            // 拿到原来的图像
            this.uavatar = this.$store.state.certification.avatar;
        },

        data() {
            return {
                file: null,
                oldFile: null,
                // host: 'http://192.168.1.108',
                uavatar: null,
                uploadSign: true,
            }
        },

        methods: {
            getAvatar() {
                let random  = Math.floor(Math.random() * (30 - 1)) + 1;
                // this.uavatar = this.host +  '/images/change/' + random + '.jpg';
                this.uavatar = '/images/change/' + random + '.jpg';
            },

            uploadAvatar(e) {
                // 判断浏览器是否支持FileReader
                if (!window.FileReader) {
                    // 关闭模态框
                    $("#updateAvatar").modal('hide');

                    // 提示
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
                    return false;
                }

                // 判断上传的是不是image
                if (!/^image/.test(this.file.type)) {

                    // 标记无法上传
                    this.uploadSign = false;

                    // 清除路径,使得上传同一文件可以触发change
                    e.srcElement.value = "" ;

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
                    // 标记无法上传
                    this.uploadSign = false;

                    // 清除路径,使得上传同一文件可以触发change
                    e.srcElement.value = "" ;

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

                this.oldFile = this.file;
                this.uploadSign = true;

                //创建一个reader
                let reader = new FileReader();
                //将图片转成base64格式
                reader.readAsDataURL(this.file);

                let _that = this;

                //读取成功后的回调
                reader.onloadend = function() {
                    _that.uavatar = this.result;
                };

                // 清除路径,使得上传同一文件可以触发change
                e.srcElement.value = "" ;
            },

            updateAvatar() {
                // 更新服务器的图像(上传图像，不是换一换)
                if (/^data:image/.test(this.uavatar)) {

                    let formData = new FormData();

                    if (!this.uploadSign) {
                        formData.append('avatar', this.oldFile);
                    }else {
                        formData.append('avatar', this.file);
                    }

                    this.$api.auth.uploadAvatar(formData).then(response => {

                        // response.data.avatar = this.host + response.data.avatar;

                        this.$store.commit('certification/updateUser', response.data);

                        $("#updateAvatar").modal('hide');

                        this.uploadSign = true;

                        this.$swal.fire({
                            type: 'success',
                            title: '头像修改成功',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2000,
                        }).then();

                    }).catch(error => {
                        if (error.response.data.errors.avatar) {
                            this.$swal.fire({
                                type: 'warning',
                                title: error.response.data.errors.avatar,
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 2500,
                            }).then();
                        }
                    })

                }else {
                    // 换一换
                    let formData = { avatar: this.uavatar };
                    this.$api.auth.changeAvatar(formData).then(response => {
                        this.$store.commit('certification/updateUser', response.data);
                        $("#updateAvatar").modal('hide');

                        this.$swal.fire({
                            type: 'success',
                            title: '头像修改成功',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2000,
                        }).then();

                    }).catch();
                }
            },

            close() {
                // 点击取消，修改为原来的图像
                this.uavatar = this.$store.state.certification.avatar;
                $("#updateAvatar").modal('hide');
            }
        }
    }
</script>

<style scoped>
    .rounded-circle {
        margin-top: 20px;
        border: solid #d9dde1 4px;
    }

    .rounded-circle:hover+.update {
        display: block;
    }

    .avator {
        cursor: pointer;
        text-align: center;
    }

    .update:hover {
        display: block;
    }

    .update {
        border-radius: 0 0 50px 50px;
        width: 86px;
        height: 22px;
        background-color: rgba(0, 0, 0, 0.64);
        position: absolute;
        top: 83px;
        left: 65px;
        cursor: pointer;
        display: none;
    }

    .modal-content {
        width: 85%;
        margin-left: 38px;
    }

    .modal-body {
        border-radius: 5px;
        padding-bottom: 0;
        background-color: #f2f4f6;
    }

    .updateAvatar {
        /*color: #3388d2;*/
        color: #1e87a5;
    }

    .updateAvatar:hover {
        /*color: #37a1f2;*/
        color: #2198b9;
    }

    .file {
        display: inline-block;
        position: absolute;
        left: 0;
        opacity: 0;
        cursor: pointer;
        width: 56px;
        height: 20px;
        font-size: 0;
    }

    .primary {
        box-shadow: none;
        color: #ffffff;
        background-color: #4d94a0;
    }

    .primary:hover {
        background-color: #50a3af;
    }

    .light {
        box-shadow: none;
        color: #ffffff;
        background-color: #b4b4b4;
    }

    .light:hover {
        background-color: #aaaaaa;
    }
</style>