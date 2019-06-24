<template>
    <li class="nav-item" @click="toManage" v-if="is_manager === 1 || is_manager === 2">
        <router-link tag="a" class="nav-link" to="/mhome">
            <span class="btn managent"><i class="fab fa-windows"></i></span>
        </router-link>
    </li>
</template>

<script>
    export default {
        name: 'TheManagement',

        inject: ['managment'],

        data() {
            return {
                is_manager: 0,
            }
        },

        created() {
            this.$api.auth.getIsManager().then(response => {
                this.is_manager = response.data.is_manager;
            }).catch(error => {

            })
        },

        methods: {
            toManage() {
                // 标志可以开启后台管理系统
                this.$store.commit('management/setManager');

                this.managment();
            }
        }
    }
</script>

<style scoped>
    a {
        margin-top: 2px;
        padding: 4px 8px 2px 8px;
    }

    .managent {
        color: #67838c;
        padding: 1px 12px 1px 12px;
        border-radius: 6px;
        border: 1px solid #dcdfe6;
        margin-right: 20px;
        background-color: rgba(76, 148, 160, 0.1)
    }

    .managent:hover {
        background-color: rgba(76, 148, 160, 0.3)
    }
</style>