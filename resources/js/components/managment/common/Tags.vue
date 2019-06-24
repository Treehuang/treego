<template>
    <div class="tags" v-if="showTags">
        <ul>
            <li class="tags-li" v-for="(item, index) in tagsList" :class="{'active': isActive(item.path)}" :key="index">
                <router-link :to="item.path" :class="[{'active': isActive(item.path)} ,'tags-li-title']">
                    {{item.title}}
                </router-link>
                <i :class="[{'active': isActive(item.path)} ,'el-icon-close']" @click="closeTag(index)"></i>
            </li>
        </ul>

        <div class="tags-close-box">
            <span class="closeOther" @click="closeOther">关闭其他</span>
            <span class="closeAll" @click="closeAll">关闭所有</span>
        </div>
    </div>
</template>

<script>

    import bus from '../../../bus/bus'

    export default {
        name: 'Tags',

        created() {
            this.setTags(this.$route);
        },

        watch:{
            $route(newValue){
                this.setTags(newValue);
            }
        },

        computed: {
            showTags() {
                return this.tagsList.length > 0;
            }
        },

        data() {
            return {
                tagsList: [
                    {
                        path: '/',
                        title: '用户授权'
                    },
                    {
                        path: '/ticketList',
                        title: '权限管理'
                    }
                ]
            }
        },

        methods: {
            isActive(path) {
                return path === this.$route.fullPath;
            },

            // 关闭单个标签
            closeTag(index) {
                const delItem = this.tagsList.splice(index, 1)[0];
                const item = this.tagsList[index] ? this.tagsList[index] : this.tagsList[index - 1];
                if (item) {
                    delItem.path === this.$route.fullPath && this.$router.push(item.path);
                }else {
                    this.$router.push('/mhome');
                }
            },

            closeAll() {
                this.tagsList = [];
                this.$router.push('/mhome');
            },

            closeOther(){
                const curItem = this.tagsList.filter(item => {
                    return item.path === this.$route.fullPath;
                });
                this.tagsList = curItem;
            },

            // 设置标签
            setTags(route) {
                const isExist = this.tagsList.some(item => {
                    return item.path === route.fullPath;
                });

                if (!isExist) {
                    if (this.tagsList.length >= 6) {
                        this.tagsList.shift();
                    }

                    this.tagsList.push({
                        title: route.meta.title,
                        path: route.fullPath,
                    });

                    bus.$emit('tags', this.tagsList);
                }
            }
        }
    }
</script>

<style scoped>
    .tags {
        position: relative;
        height: 24px;
        overflow: hidden;
        background: rgba(76, 148, 160, 0.01);
        border-bottom: 1px solid #dee2e6;
        box-shadow: 0 5px 10px #ddd;
    }
    .tags ul {
        padding-left: 0;
        box-sizing: border-box;
        width: 100%;
        height: 100%;
    }
    .tags-li {
        float: left;
        font-size: 12px;
        overflow: hidden;
        cursor: pointer;
        height: 23px;
        line-height: 24px;
        border: 0;
        background: rgba(76, 148, 160, 0.01);
        padding-right: 5px;
        margin-right: 1px;
        vertical-align: middle;
        color: #45555d;
        -webkit-transition: all .3s ease-in;
        -moz-transition: all .3s ease-in;
        transition: all .3s ease-in;
    }

    .tags-li.active, .tags-li:hover {
        color: #ffff;
        background-color: #445a73;
    }

    a.active, .el-icon-close.active, .tags-li:hover a {
        color: #ffffff;
    }

    .tags-li:hover .el-icon-close {
        color: #ffffff;
    }

    .tags-li-title {
        padding: 5px 0 5px 15px;
        color: #45555d;
    }

    .closeAll, .closeOther {
        border: 1px solid #dcdfe6;
        border-radius: 3px;
        padding: 2px 6px 2px 6px;
        color: #45555d;
        font-size: 12px;
    }

    .closeAll:hover, .closeOther:hover {
        cursor: pointer;
        color: #1ab394;
        border: 1px solid rgba(76, 148, 160, 0.15);
        background-color: rgba(76, 148, 160, 0.15);
    }

    .tags-close-box {
        position: absolute;
        right: 10px;
        top: 0;
        box-sizing: border-box;
        text-align: center;
        width: 140px;
        /*height: 30px;*/
        z-index: 10;
    }
</style>