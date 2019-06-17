<template>
    <div class="container">

        <noitces></noitces>

        <div v-if="noMess">
            <div class="noMess">
                <span>暂无数据</span>
            </div>
            <hr>
        </div>

        <div class="item" v-for="(item, index) in recyleList">
            <div class="messages">
                <span>{{ item.mess }}</span>
            </div>

            <div>
                <span class="time">{{ item.created_at }}</span>
                <span class="restore" @click="restore(index, item.message_id)">还原</span>
                <span class="empty" @click="emptyOne(index, item.message_id)">清除</span>
            </div>

            <hr>
        </div>

        <button v-show="showButton" class="btn emptyAll" @click="emptyAll">清空回收站</button>

    </div>
</template>

<script>

    import Noitces from './Notices';

    export default {
        name: 'Recyle',

        components: {
            Noitces,
        },

        created() {
            this.$store.dispatch('messages/getRecyle').then(response => {
                if (response.data.recyleList.length !== 0) {
                    this.recyleList = response.data.recyleList;
                    this.showButton = true;
                }else {
                    this.noMess = true;
                    this.showButton = true;
                }
            }).catch(() => {
                this.$swal.fire({
                    type: 'error',
                    title: '糟糕，回收站消息模块出问题了~',
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500,
                }).then();
            })
        },

        data() {
            return {
                noMess: false,
                showButton: false,
                recyleList: [],
                messageIdList: [],
            }
        },

        methods: {
            restore(index, message_id) {
                this.$store.dispatch('messages/restoreRecyle', {message_id: message_id}).then(() => {
                    this.recyleList.splice(index, 1);

                    if (this.recyleList.length === 0) {
                        this.noMess = true;
                    }
                }).catch(() => {
                    this.$swal.fire({
                        type: 'error',
                        title: '糟糕，还原消息失败~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                })
            },

            emptyAll() {

                if (this.recyleList.length === 0 ) {
                    return;
                }

                for (let i = 0, len = this.recyleList.length; i < len; i++) {
                    this.messageIdList.push(this.recyleList[i].message_id);
                }

                this.$store.dispatch('messages/emptyAll', {messageIdList: this.messageIdList}).then(() => {
                    this.recyleList.splice(0, this.recyleList.length);
                    this.noMess = true;
                }).catch(() => {
                    this.$swal.fire({
                        type: 'error',
                        title: '糟糕，回收站清空失败~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                });
            },

            emptyOne(index, message_id) {
                this.$store.dispatch('messages/emptyOne', { message_id: message_id }).then(() => {
                    this.recyleList.splice(index, 1);
                    if (this.recyleList.length === 0) {
                        this.noMess = true;
                    }
                }).catch(() => {
                    this.$swal.fire({
                        type: 'error',
                        title: '糟糕，清除消息失败~',
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
        margin-right: 70px;
    }

    .noMess {
        text-align: center;
        color: #909399;
    }

    .restore {
        border: 1px solid #dcdfe6;
        border-radius: 3px;
        padding: 5px 10px 5px 10px;
        color: #45555d;
        font-size: 12px;
        margin-right: 15px;
    }

    .restore:hover {
        cursor: pointer;
        color: #4c94a0;
        border: 1px solid rgba(76, 148, 160, 0.15);
        background-color: rgba(76, 148, 160, 0.15);
    }

    .emptyAll {
        box-shadow: none;
        color: #ffffff;
        background-color: #ee6763;
    }

    .emptyAll:hover {
        background-color: #ff6f6b;
    }

    .empty {
        border: 1px solid #dcdfe6;
        border-radius: 3px;
        padding: 5px 10px 5px 10px;
        color: #45555d;
        font-size: 12px;
    }

    .empty:hover {
        cursor: pointer;
        color: #f56c6c;
        border: 1px solid rgba(245, 108, 108, 0.3);
        background-color: rgba(245, 108, 108, 0.3);
    }
</style>