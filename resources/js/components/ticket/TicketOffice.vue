<template>
    <div class="container">
        <div class="drag-box">

            <div class="drag-box-item">
                <div class="item-title">
                    <span>起始点</span>
                    <i class="far fa-hand-point-right"></i>
                    <div class="btn sure">查看</div></div>
                <div></div>

                <draggable v-model="startPlaceList" :options="dragOptions">
                    <transition-group tag="div" id="todo" class="item-ul">
                        <div v-for="item in startPlaceList" class="drag-list" :key="item.id">
                            <the-ticket :ticket="item"></the-ticket>
                        </div>
                    </transition-group>
                </draggable>
            </div>

            <div class="drag-box-item">
                <div class="item-title">
                    <span>大学城</span>
                    <i class="far fa-hand-point-right"></i>
                    <div class="btn sure" @click="finds">保存</div>
                </div>

                <draggable v-model="universityList" :options="dragOptions">
                    <transition-group tag="div" id="doing" class="item-ul">
                        <div v-for="item in universityList" class="drag-list" :key="item.id">
                            <the-ticket :ticket="item"></the-ticket>
                        </div>
                    </transition-group>
                </draggable>
            </div>

            <div class="drag-box-item">
                <div class="item-title">
                    <span>桂花岗</span>
                    <i class="far fa-hand-point-right"></i>
                    <div class="btn sure">保存</div>
                </div>

                <draggable v-model="osmanthusList" :options="dragOptions">
                    <transition-group tag="div" id="done" class="item-ul">
                        <div v-for="item in osmanthusList" class="drag-list" :key="item.id">
                            <the-ticket :ticket="item"></the-ticket>
                        </div>
                    </transition-group>
                </draggable>
            </div>

        </div>
    </div>
</template>

<script>

    import draggable from 'vuedraggable';
    import TheTicket from './TheTicket';

    export default {
        name: 'TicketOffice',

        components:{
            draggable,
            TheTicket,
        },


        created() {
            // 解决拖拽出现搜索（针对火狐浏览器）
            document.body.ondrop = function (event) {
                event.preventDefault();
                event.stopPropagation();
            }
        },

        data() {
            return {
                dragOptions:{
                    animation: 150,
                    scroll: true,
                    group: 'sortlist',
                    ghostClass: 'ghost-style',
                },
                startPlaceList: [
                    {
                        id: 1,
                        ticket_num: 1,
                        from_time: '18:30',
                        arrival_time: '22:30',
                        start_place: '西胪综合市场',
                    },
                    {
                        id: 2,
                        ticket_num: 1,
                        from_time: '18:45',
                        arrival_time: '22:30',
                        start_place: '潮阳国税局',
                    },
                    {
                        id: 3,
                        ticket_num: 1,
                        from_time: '18:50',
                        arrival_time: '22:30',
                        start_place: '谷饶路口',
                    },
                    {
                        id: 4,
                        ticket_num: 1,
                        from_time: '18:45',
                        arrival_time: '22:30',
                        start_place: '六都中学',
                    }
                ],
                universityList: [],
                osmanthusList:[]
            }
        },

        methods: {
            finds() {
                console.log(this.universityList);
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
        -webkit-box-shadow: -4px 7px 10px 2px rgba(82, 168, 180, 0.2);
        -moz-box-shadow: -4px 7px 10px 2px rgba(80, 168, 180, 0.2);
        -o-box-shadow: -4px 7px 10px 2px rgba(80, 168, 180, 0.2);
        box-shadow: -3px 8px 12px 2px rgba(81, 168, 180, 0.2);

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
</style>