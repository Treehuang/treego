<template>
    <div class="container">
        <!-- 遮罩层 -->
        <div class='popContainer' v-show="pop"></div>

        <div class="drag-box">

            <div class="drag-box-item">
                <div class="item-title">
                    <span>起始点</span>
                    <i class="far fa-hand-point-right"></i>
                    <i class="fas fa-yen-sign"> {{ price }}</i>
                </div>

                <draggable v-model="startPlaceTicketList" :options="dragOptions">
                    <transition-group tag="div" id="todo" class="item-ul">
                        <div v-for="item in startPlaceTicketList" class="drag-list" :key="item.id">
                            <the-ticket :ticket="item"></the-ticket>
                        </div>
                    </transition-group>
                </draggable>
            </div>

            <div class="drag-box-item">
                <div class="item-title">
                    <span>大学城</span>
                    <i class="far fa-hand-point-right"></i>
                    <div class="btn sure" @click="addToUniversity">保存</div>
                </div>

                <draggable v-model="universityTicketList" :options="dragOptions">
                    <transition-group tag="div" id="doing" class="item-ul">
                        <div v-for="item in universityTicketList" class="drag-list" :key="item.id">
                            <the-ticket :ticket="item"></the-ticket>
                        </div>
                    </transition-group>
                </draggable>
            </div>

            <div class="drag-box-item">
                <div class="item-title">
                    <span>桂花岗</span>
                    <i class="far fa-hand-point-right"></i>
                    <div class="btn sure" @click="toOsmanthus">保存</div>
                </div>

                <draggable v-model="osmanthusTicketList" :options="dragOptions">
                    <transition-group tag="div" id="done" class="item-ul">
                        <div v-for="item in osmanthusTicketList" class="drag-list" :key="item.id">
                            <the-ticket :ticket="item"></the-ticket>
                        </div>
                    </transition-group>
                </draggable>
            </div>

        </div>
    </div>
</template>

<script>

    import { mapState } from 'vuex';

    import draggable from 'vuedraggable';
    import TheTicket from './TheTicket';

    export default {
        name: 'TicketOffice',

        components:{
            draggable,
            TheTicket,
        },

        computed: {
            ...mapState({
                isAuth : state => state.certification.is_auth
            })
        },

        created() {

            // 解决拖拽出现搜索（针对火狐浏览器）
            document.body.ondrop = function (event) {
                event.preventDefault();
                event.stopPropagation();
            };

            // 判断系统是否开放
            this.$api.ticket.getTicketsOfficeState().then(response => {
                // 未开放
                if (response.data.ticketsOfficeState === 0) {
                    this.pop = true;
                    this.$swal.fire({
                        type: 'warning',
                        title: 'sorry，系统暂未开放~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                    }).then(() => {
                        this.$router.push({name: 'home'});
                    });
                }else {
                    // 加载页面车票（分两种情况，一种是登录的用户，一种是游客）
                    // 登录的用户
                    if (this.isAuth) {
                        this.$api.ticket.getAuthTickets().then(response => {
                            this.price = this.startPlaceTicketList = response.data.startPlaceTicketList[0].price;
                            this.osmanthusTicketList  = response.data.osmanthusTicketList;
                            this.startPlaceTicketList = response.data.startPlaceTicketList;
                            this.universityTicketList = response.data.universityTicketList;
                        }).catch(() => {
                            this.$swal.fire({
                                type: 'error',
                                title: '您获取tickets失败~',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 2500,
                            })
                        });
                    }else {
                        // 游客
                        this.$api.ticket.getNoAuthTickets().then(response => {
                            this.startPlaceTicketList = response.data.startPlaceTicketList;
                        }).catch(() => {
                            this.$swal.fire({
                                type: 'error',
                                title: '游客获取tickets失败~',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 2500,
                            })
                        });
                    }
                }

            }).catch(() => {
                this.$swal.fire({
                    type: 'warning',
                    title: '系统正在维护~',
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 2500,
                }).then(() => {
                    this.$router.push({name: 'home'});
                });
            });
        },

        data() {
            return {

                dragOptions:{
                    animation: 150,
                    scroll: true,
                    group: 'sortlist',
                    ghostClass: 'ghost-style',
                },

                pop: false,
                price: '',
                startPlaceTicketList: [],
                universityTicketList: [],
                osmanthusTicketList:[]
            }
        },

        methods: {
            addToUniversity() {

                if (this.isAuth) {
                    let formData = { universityTicketList: this.universityTicketList };
                    this.$api.ticket.addToUniversity(formData).then(() => {
                        this.$swal.fire({
                            type: 'success',
                            title: '保存成功~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        })
                    }).catch(() => {
                        this.$swal.fire({
                            type: 'error',
                            title: '网络问题，保存失败~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        })
                    });
                }else {
                    this.pop = true;
                    this.$swal.fire({
                        type: 'warning',
                        title: '保存失败，您需要登录才可以进行此操作~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then(() => { this.pop = false; })
                }
            },

            toOsmanthus() {

                if (this.isAuth) {
                    let formData = { osmanthusTicketList: this.osmanthusTicketList };
                    this.$api.ticket.addToOsmanthus(formData).then(() => {
                        this.$swal.fire({
                            type: 'success',
                            title: '保存成功~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        })
                    }).catch(() => {
                        this.$swal.fire({
                            type: 'error',
                            title: '网络问题，保存失败~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        })
                    });
                }else {
                    this.pop = true;
                    this.$swal.fire({
                        type: 'warning',
                        title: '保存失败，您需要登录才可以进行此操作~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then(() => { this.pop = false })
                }
            },
        }
    }
</script>

<style scoped>
    .container {
        min-width: 1100px;
    }

    .drag-box{
        display: flex;
        user-select: none;
    }

    .drag-box-item {
        flex: 1;
        height: 495px;
        max-width: 370px;
        min-width: 356px;
        background-color: #fafbfc;
        margin-right: 16px;
        border-radius: 6px;
        border: 1px #fafbfc solid;
        -webkit-box-shadow: -2px 3px 6px 3px rgba(81, 168, 180, 0.2);
        -moz-box-shadow: -2px 3px 6px 3px rgba(81, 168, 180, 0.2);
        -o-box-shadow: -2px 3px 6px 3px rgba(81, 168, 180, 0.2);
        box-shadow: -2px 3px 6px 3px rgba(81, 168, 180, 0.2);

        /* 去掉纵向滚动条 */
        overflow: hidden;
    }

    .item-title{
        padding: 10px 12px 4px 12px;
        color: #45555d;
        font-size: 16px;
    }
    .item-ul {
        /* 去掉纵向滚动条 */
        width: 371px;

        padding: 0 8px 8px;
        height: 443px;
        overflow-y: scroll;

        /* 去掉横向滚动条 */
        overflow-x: hidden;

        /*-ms-overflow-style: none;    !*IE 10+ 去掉滚动条*!*/
    }

    /* chrome 去掉滚动条*/
    /*.item-ul::-webkit-scrollbar{*/
        /*width: 0;*/
        /*display: none;*/
    /*}*/

    .drag-list {
        margin: 5px 0 10px;
        -webkit-transition: border .3s ease-in;
        transition: border .3s ease-in;
    }

    .drag-list:hover {
        cursor: pointer;
    }

    .ghost-style{
        display: block;
        color: transparent;
        border-style: dashed
    }

    .sure {
        float: right;
        border: 1px solid #dcdfe6;
        border-radius: 4px;
        padding: 1px 10px 1px 10px;
        color: #45555d;
        font-size: 12px;
        margin-top: 1px;
    }

    .sure:hover {
        cursor: pointer;
        color: #4c94a0;
        border: 1px solid rgba(76, 148, 160, 0.15);
        background-color: rgba(76, 148, 160, 0.15);
    }

    .fa-hand-point-right {
        margin-left: 200px;
    }

    .fa-yen-sign {
        color: #45555d;
        float: right;
        margin-top: 5.5px;
        font-size: 14px
    }

    .popContainer {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index:3;
        background: rgba(0, 0, 0, 0.3);
    }
</style>