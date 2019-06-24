<template>
    <li class="nav-item">
        <router-link tag="a" class="nav-link" to="/ticketList">
            <span class="btn myticket"><i class="fas fa-ticket-alt"></i> ticket</span>
        </router-link>
    </li>
</template>

<script>
    export default {
        name: 'MyTicket',
    }
</script>

<style scoped>
    .myticket{
        color: #67838c;
        padding: 1px 12px 1px 12px;
        border-radius: 6px;
        border: 1px solid #dcdfe6;
        margin-right: 20px;
        font-weight: bold;
        background-color: rgba(76, 148, 160, 0.1);
    }

    .myticket:hover {
        cursor: pointer;
        /*color: #4c94a0;*/
        /*border: 1px solid rgba(76, 148, 160, 0.3);*/
        background-color: rgba(76, 148, 160, 0.2);
    }

    a {
        margin-top: 2px;
        padding: 4px 8px 2px 8px;
    }

    /*.fa-ticket-alt {*/
        /*color: #526870;*/
    /*}*/
</style>