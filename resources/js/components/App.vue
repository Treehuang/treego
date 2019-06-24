<template>
    <div class="wraper">

        <!-- 网站 -->
        <div class="average-user" v-if="isAverageUser && is_manager === 0">
            <the-header></the-header>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
            <the-footer></the-footer>
        </div>

        <!-- 网站后台管理 -->
        <div class="manager" v-if="isManager && is_manager === 1">
            <manage-header></manage-header>
            <side-bar></side-bar>
            <div class="content-box" :class="{'content-collapse': collapse}">
                <div class="content">
                    <the-tags></the-tags>
                    <transition name="move" mode="out-in">
                        <keep-alive :include="tagsList">
                            <router-view></router-view>
                        </keep-alive>
                    </transition>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

    import { mapState } from 'vuex'
    import TheHeader from './common/TheHeader'
    import TheFooter from './common/TheFooter'
    import SideBar from './managment/common/SideBar'
    import ManageHeader from './managment/common/ManageHeader'
    import TheTags from './managment/common/Tags'
    import bus from '../bus/bus';

    export default {

        components: {
            ManageHeader,
            TheHeader,
            TheFooter,
            SideBar,
            TheTags
        },

        created(){
            bus.$on('collapse', msg => {
                this.collapse = msg;
            });

            // 只有在标签页列表里的页面才使用keep-alive，即关闭标签之后就不保存到内存中了。
            bus.$on('tags', msg => {
                let arr = [];
                for(let i = 0, len = msg.length; i < len; i ++){
                    msg[i].name && arr.push(msg[i].name);
                }
                this.tagsList = arr;
            });
        },

        provide() {
            return {
                managment: this.managment,
                exitManagement: this.exitManagement,
            }
        },

        computed: {
            ...mapState({
                is_manager : state => state.management.is_manager
            })
        },

        data() {
            return {
                tagsList: [],
                collapse: true,
                isManager: true,
                isAverageUser: true,
            }
        },

        methods: {

            managment() {
                this.collapse = true;
                this.isAverageUser = false;
                this.$nextTick(function () {
                    this.isManager = true;
                })
            },

            exitManagement() {
                this.isAverageUser = true;
                this.$nextTick(function () {
                    this.isManager = false;
                })
            }
        }
    }
</script>

<style scoped>

    .content-box {
        width: auto;
        position: absolute;
        left: 205px;
        right: 0;
        top: 56px;
        bottom: 0;
        transition: left .3s ease-in-out;
        background: #f0f0f0;
    }

    .content {
        width: auto;
        height: 100%;
        padding: 0;
        overflow-y: scroll;
        box-sizing: border-box;
        -ms-overflow-style: none;
    }

    .content::-webkit-scrollbar {
        width: 0;
    }

    .content-collapse {
        left: 64px !important;
    }
</style>