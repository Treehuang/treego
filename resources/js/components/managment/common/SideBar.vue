<template>
    <div class="sidebar">
            <el-menu class="sidebar-el-menu" :default-active="onRoutes" :collapse="collapse" background-color="#324157"
                     text-color="#bfcbd9" active-text-color="#ffffff" unique-opened router>

                <template v-for="item in items">

                    <template v-if="item.subs">
                        <el-submenu :index="item.index" :key="item.index">
                            <template slot="title">

                                <el-badge v-if="item.icon === 'el-icon-mobile' && ticketsOfficeState === 1" is-dot type="success">
                                    <i class="el-icon-mobile"></i>
                                </el-badge>
                                <el-badge v-else-if="item.icon === 'el-icon-mobile' && ticketsOfficeState === 0" is-dot type="info">
                                    <i class="el-icon-mobile"></i>
                                </el-badge>
                                <i v-else :class="item.icon"></i>

                                <span slot="title">{{ item.title }}</span>

                            </template>

                            <template v-for="subItem in item.subs">
                                <el-submenu v-if="subItem.subs" :index="subItem.index" :key="subItem.index">
                                    <template slot="title">{{ subItem.title }}</template>
                                    <el-menu-item v-for="(threeItem,i) in subItem.subs" :key="i" :index="threeItem.index" style="padding-left: 65px">
                                        {{ threeItem.title }}
                                    </el-menu-item>
                                </el-submenu>
                                <el-menu-item v-else :index="subItem.index" :key="subItem.index" style="padding-left: 65px;">
                                    {{ subItem.title }}
                                </el-menu-item>
                            </template>

                        </el-submenu>
                    </template>

                    <template v-else>
                        <el-menu-item :index="item.index" :key="item.index">

                            <el-badge v-if="item.icon === 'el-icon-document-checked' && no_audit_num !== 0" :value="no_audit_num" :max="9">
                                <i class="el-icon-document-checked"></i>
                            </el-badge>
                            <i v-else :class="item.icon"></i>

                            <span v-if="item.index === 'mhome'">{{ item.title }}</span>
                            <span v-else slot="title">{{ item.title }}</span>
                        </el-menu-item>
                    </template>

                </template>
            </el-menu>
        </div>
</template>

<script>

    import bus from '../../../bus/bus';

    export default {
        name: 'SideBar',

        created(){
            // 通过 Event Bus 进行组件间通信，来折叠侧边栏
            bus.$on('collapse', msg => {
                this.collapse = msg;
            });

            // 通过 Event Bus 进行组件间通信，来读取待审核学籍的个数
            bus.$on('get_no_audit_num', msg => {
                this.no_audit_num = msg;
            });

            // 通过 Event Bus 进行组件间通信，来读取车票系统是否开启
            bus.$on('get_ticket_state', msg => {
                this.ticketsOfficeState = msg;
            });

            // 获取待审核学籍证明
            this.$api.management_audit.getNoAudits().then(response => {
                this.no_audit_num = response.data.data.length;
            });

            // 获取车票系统是否开启
            this.$api.ticket.getTicketsOfficeState().then(response => {
                this.ticketsOfficeState = response.data.ticketsOfficeState;
            })
        },

        computed:{
            onRoutes(){
                return this.$route.path.replace('/','');
            }
        },

        data() {
            return {
                collapse: true,
                no_audit_num: 0,
                ticketsOfficeState: 0,
                items: [
                    {
                        icon: 'el-icon-s-home',
                        index: 'mhome',
                        title: '系统首页'
                    },
                    {
                        icon: 'el-icon-user-solid',
                        index: 'maccount',
                        title: '用户管理',
                    },
                    {
                        icon: 'el-icon-document-checked',
                        index: 'certificatCheck',
                        title: '学籍审核',
                    },
                    // {
                    //     icon: 'el-icon-message-solid',
                    //     index: 'w',
                    //     title: '消息中心',
                    //     subs: [
                    //         {
                    //             index: 's',
                    //             title: '系统公告'
                    //         },
                    //         {
                    //             index: 'h',
                    //             title: '短信通知',
                    //         },
                    //     ]
                    // },
                    {
                        icon: 'el-icon-mobile',
                        index: 'k',
                        title: '车票系统',
                        subs: [
                            {
                              index: 'setTickets',
                              title: '添加车票',
                            },
                            {
                                index: 'opeTickets',
                                title: '管理车票',
                            },
                            {
                                index: 'statisticalCenter',
                                title: '统计中心'
                            },
                            {
                                index: 'exportdown',
                                title: '下载导出',
                            }
                        ]
                    }
                ]
            };
        },

        methods: {

        }
    }
</script>

<style scoped>
    .sidebar {
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        bottom:0;
        /*overflow-y: scroll;*/
        -ms-overflow-style: none;
    }
    .sidebar::-webkit-scrollbar {
        width: 0;
    }

    .sidebar-el-menu {
        border: 0;
    }

    .sidebar-el-menu:not(.el-menu--collapse){
        width: 205px;
    }
    .sidebar > ul {
        height:100%;
    }

    .el-menu-item.is-active {
        background-color: #283446 !important;
    }

    /deep/ .el-badge__content.is-fixed {
        position: absolute;
        top: 15px;
        right: 15px;
        -webkit-transform: translateY(-50%) translateX(100%);
        transform: translateY(-50%) translateX(100%);
    }

    /deep/ .el-badge__content.is-dot {
        height: 10px;
        width: 10px;
        padding: 0;
        right: 0;
        border-radius: 50%;
    }

    /deep/ .el-badge__content--success {
        background-color: #1ab394;
    }
</style>