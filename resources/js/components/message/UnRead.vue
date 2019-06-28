<template>
    <div class="container">

        <noitces></noitces>

        <div v-if="noMess">
            <div class="noMess">
                <span>暂无数据</span>
            </div>
            <hr>
        </div>

        <div class="item" v-for="(item, index) in messList">
            <div class="messages">
                <span>{{ item.mess }}</span>
            </div>

            <div>
                <span class="time">{{ item.created_at }}</span>
                <span class="read" @click="markRead(index)">标为已读</span>
            </div>

            <hr>
        </div>

       <button v-show="showButton" class="btn allMark" @click="removeAll">全部标为已读</button>

    </div>
</template>

<script>

    import Noitces from './Notices';

    export default {
        name: 'UnRead',

        components: {
            Noitces,
        },

        created() {
            // 获取未读消息
            this.$store.dispatch('messages/getUnread').then(response => {
                if (response.data.unReadList.length !== 0) {
                    this.messList = response.data.unReadList;

                    // 先销毁tooltip，再激活tooltip(不能去掉！)
                    // $(function () { $("[data-toggle='tooltip']").tooltip('dispose'); });
                    // $(function () { $("[data-toggle='tooltip']").tooltip(); });

                    this.showButton = true;
                }else {
                    this.noMess = true;
                    this.showButton = true;
                }
            }).catch(() => {
                this.$swal.fire({
                    type: 'error',
                    title: '糟糕，未读消息模块出问题了~',
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500,
                }).then();
            });
        },

        data() {
            return {
                noMess: false,
                showButton: false,
                messList: [],
            }
        },

        methods: {

            markRead(index) {

                let formData = {
                    message_id: this.messList[index].id,
                    mess: this.messList[index].mess,
                    created_at: this.messList[index].created_at,
                };

                // 将标为已读的消息传给后端
                this.$store.dispatch('messages/markRead', formData).then(() => {
                    this.messList.splice(index, 1);

                    if (this.messList.length === 0) {
                        this.noMess = true;
                    }

                    // 先销毁tooltip，再激活tooltip(不能去掉！)
                    // $(function () { $("[data-toggle='tooltip']").tooltip('dispose'); });
                    // $(function () { $("[data-toggle='tooltip']").tooltip(); });

                }).catch(() => {
                    this.$swal.fire({
                        type: 'error',
                        title: '糟糕，标为已读失败~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                });
            },

            removeAll() {

                if (this.messList.length === 0) {
                    return;
                }

                this.$store.dispatch('messages/markAll', { messList: this.messList }).then(() => {

                    this.messList.splice(0, this.messList.length);
                    this.noMess = true;

                    // 先销毁tooltip，再激活tooltip(不能去掉！)
                    // $(function () { $("[data-toggle='tooltip']").tooltip('dispose'); });
                    // $(function () { $("[data-toggle='tooltip']").tooltip(); });

                }).catch(() => {
                    this.$swal.fire({
                        type: 'error',
                        title: '糟糕，标记全部为已读失败~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                });
            }
        }
    }
</script>

<style scoped>
    .container {
        min-width: 1068px;
    }

    hr {
        border-top: 1px solid rgb(222, 233, 234);
    }

    .read {
        color: #4c94a0;
        border: 1px solid rgba(76, 148, 160, 0.25);
        background-color: rgba(76, 148, 160, 0.1);
        border-radius: 3px;
        padding: 5px 10px 5px 10px;
        font-size: 12px;
    }

    .read:hover {
        cursor: pointer;
        color: #ffffff;
        background-color: #4d94a0;
    }

    .item {
        min-width: 1068px;
    }

    .messages {
        float: left;
        width: 750px;
        color: #45555d;
    }

    .time {
        color: #45555d;
        margin-right: 120px;
    }

    .noMess {
        text-align: center;
        color: #909399;
    }

    .allMark {
        box-shadow: none;
        color: #ffffff;
        background-color: #4d94a0;
    }

    .allMark:hover {
        background-color: #50a3af;
    }
</style>