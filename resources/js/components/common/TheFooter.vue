<template>
    <footer class="footer">
        <div class="container">
            <p class="float-left">
                由 <a href="https://github.com/Treehuang" target="_blank">tree</a> 设计和编码
            </p>

            <p class="float-right contact" data-toggle="modal" data-target="#image">联系我</p>
        </div>

        <!-- 添加微信模态框 -->
        <div class="modal fade" id="image" tabindex="-1" role="dialog" aria-labelledby="suggest" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="width: 332px; margin-left: 84px">
                    <div class="modal-body">
                        <img :src="imageUrl" width="300px" height="300px"/>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button type="button" class="btn primary" data-dismiss="modal">确定</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- 反馈意见模态框 -->
        <i class="fas fa-pen shadow-sm mb-5 bg-white rounded" data-toggle="modal" data-target="#suggest"></i>

        <div class="modal fade" id="suggest" tabindex="-1" role="dialog" aria-labelledby="suggest" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">

                        <h5 style="float: left; color: #545454">反馈与建议</h5>

                        <div class="clearfix"></div>

                        <div>
                            <label for="message"></label>
                            <textarea class="form-control" id="message" rows="5"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button class="btn light" data-dismiss="modal">取消</button>
                            <button type="submit" class="btn primary">确定</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </footer>
</template>

<script>

    export default {
        name: 'TheFooter',

        data() {
            return {
                imageUrl: '/images/my/me.jpg',
            }
        },
    }

</script>

<style scoped>
    a {
        color: inherit;
        text-decoration: none;
    }

    a:hover {
        color: #ffffff;
    }

    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        min-width: 1100px;
        /* Set the fixed height of the footer here */
        height: 60px;
        background-color: #1b1c1d;
    }

    p {
        margin: 19px 0;
        color: #c1c1c1;
    }

    .container {
        padding-left: 15px;
        padding-right: 15px;
    }

    .contact {
        margin-right: 10px;
    }

    .contact:hover {
        color: #ffffff;
        cursor: pointer;
    }

    .fa-pen {
        border: solid 1px #DDD;
        box-shadow: none;
        position: absolute;
        right: 30px;
        bottom: 40px;
        font-size: 15px;
        padding: 7px;
        color: #999;
        border-radius: 5px;
    }

    .fa-pen:hover {
        cursor: pointer;
        color: #4c94a0;
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

    .modal-body {
        padding-bottom: 0;
    }

    .form-control {
        margin-bottom: 28px;
    }
</style>