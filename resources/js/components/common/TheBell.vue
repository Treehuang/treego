<template>
    <li class="nav-item bell">
        <router-link class="nav-link" to="/unread">
            <!--<i class="far fa-bell" @click="hideTool" id="element" data-toggle="tooltip" data-placement="bottom" :title="`有${unReadNum}条未读消息`">-->
                <!--<span v-show="showUnread" class="bell-badge"></span>-->
            <!--</i>-->

            <el-badge v-if="unReadNum !== 0" :value="unReadNum" :max="8">
                <i class="far fa-bell"></i>
            </el-badge>

            <i v-else class="far fa-bell"></i>
        </router-link>
    </li>
</template>

<script>

    import { mapState } from 'vuex';

    export default {
        name: 'TheBell',

        computed: {
            ...mapState({
                unReadNum : state => state.messages.unReadNum,
            }),
        },

        created() {
            // 后端获取未读消息
            this.$store.dispatch('messages/getUnread').then(() => {

                // if (response.data.unReadList.length !== 0) {
                //     this.showUnread = true;
                // }
                //
                // // 激活tooltip(为的是先将vuex的数据设置好再激活，不然会出现两个tooltip)
                // $(function () { $("[data-toggle='tooltip']").tooltip(); });

            }).catch(() => {
                this.$swal.fire({
                    type: 'error',
                    title: '糟糕，总消息模块出问题了~',
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500,
                }).then();
            });

            // 设置已读消息数目
            this.$store.dispatch('messages/getRead').then(() => {}).catch(() => {
                this.$swal.fire({
                    type: 'error',
                    title: '糟糕，已读消息模块出问题了~',
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500,
                }).then();
            });

            // 设置回收站消息数目
            this.$store.dispatch('messages/getRecyle').then(() => {}).catch(() => {
                this.$swal.fire({
                    type: 'error',
                    title: '糟糕，回收站消息模块出问题了~',
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500,
                }).then();
            });

        },

        data() {
            return {
                showUnread: false,
            }
        },
    }
</script>

<style scoped>
    .fa-bell {
        margin-top: 2px;
        color: #67838c;
        font-size: 18px;
        position: relative;
        padding-bottom: 4px;
    }

    .bell {
        width: 32px;
        margin-right: 15px;
    }
</style>