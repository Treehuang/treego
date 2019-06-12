<template>

    <div class="container">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <router-link data-toggle="tab" to="/unread" :class="[{'active': this.$route.path === '/unread'}, 'nav-link']" role="tab">未读消息({{ unReadNum }})</router-link>
            </li>
            <li class="nav-item">
                <router-link data-toggle="tab" to="/read"   :class="[{'active': this.$route.path === '/read'}, 'nav-link']" role="tab">已读消息({{ readNum }})</router-link>
            </li>
            <li class="nav-item">
                <router-link data-toggle="tab" to="/recyle" :class="[{'active': this.$route.path === '/recyle'}, 'nav-link']" role="tab">回收站({{ recycleNum }})</router-link>
            </li>
        </ul>
    </div>

</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'Notices',

        computed: {
            ...mapState({
                readNum : state => state.messages.readNum,
                unReadNum : state => state.messages.unReadNum,
                recycleNum: state => state.messages.recycleNum,
            })
        },

        data() {
            return {
            }
        }
    }
</script>

<style scoped>
    a {
        color: #4c94a0;
    }

    .container {
        min-width: 1100px;
        margin-bottom: 40px;
        padding: 0;
        margin-top: 50px
    }
</style>