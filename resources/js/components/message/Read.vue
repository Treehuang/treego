<template>
    <div class="container">

        <noitces></noitces>

        <div v-if="noMess">
            <div class="noMess">
                <span>暂无数据</span>
            </div>
            <hr>
        </div>

        <div class="item" v-for="(item, index) in readList">
            <div class="messages">
                <span>{{ item.mess }}</span>
            </div>

            <div>
                <span class="time">{{ item.created_at }}</span>
                <span class="destory" @click="removeRead(index, item.message_id)">删除</span>
            </div>

            <hr>
        </div>

        <button v-show="showButton" class="btn removeAll" @click="removeAll">全部删除</button>

    </div>
</template>

<script>

    import Noitces from './Notices';

    export default {
        name: 'Read',

        components: {
            Noitces,
        },

        created() {
            this.$store.dispatch('messages/getRead').then(response => {
                if (response.data.readList.length !== 0) {
                    this.readList = response.data.readList;
                    this.showButton = true;
                }else {
                    this.noMess = true;
                    this.showButton = true;
                }
            }).catch(() => {
                this.$swal.fire({
                    type: 'error',
                    title: '糟糕，已读消息模块出问题了~',
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
                readList: [],
                messageIdList: [],
            }
        },

        methods: {
            removeRead(index, message_id) {
                this.$store.dispatch('messages/removeRead', { message_id: message_id }).then(() => {
                    this.readList.splice(index, 1);

                    if (this.readList.length === 0) {
                        this.noMess = true;
                    }
                }).catch(() => {
                    this.$swal.fire({
                        type: 'error',
                        title: '糟糕，删除消息失败~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                });
            },

            removeAll() {

                if (this.readList.length === 0 ) {
                    return;
                }

                for (let i = 0, len = this.readList.length; i < len; i++) {
                    this.messageIdList.push(this.readList[i].message_id);
                }

                this.$store.dispatch('messages/removeAll', {messageIdList: this.messageIdList}).then(() => {
                    this.readList.splice(0, this.readList.length);
                    this.noMess = true;
                }).catch(() => {
                    this.$swal.fire({
                        type: 'error',
                        title: '糟糕，删除全部消息失败~',
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

    .noMess {
        text-align: center;
        color: #909399;
    }

    .item {
        min-width: 1068px;
    }

    .time {
        color: #45555d;
        margin-right: 120px;
    }

    .messages {
        float: left;
        width: 750px;
        color: #45555d;
    }

    .destory {
        border: 1px solid #dcdfe6;
        border-radius: 3px;
        padding: 5px 10px 5px 10px;
        color: #45555d;
        font-size: 12px;
    }

    .destory:hover {
        cursor: pointer;
        color: #f56c6c;
        border: 1px solid rgba(245, 108, 108, 0.3);
        background-color: rgba(245, 108, 108, 0.3);
    }

    .removeAll {
        box-shadow: none;
        color: #ffffff;
        background-color: #ee6763;
    }

    .removeAll:hover {
        background-color: #ff6f6b;
    }
</style>