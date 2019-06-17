<template>
    <li class="nav-item">
        <router-link tag="a" class="nav-link" to="/unread">
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
        padding: 3px 12px 3px 12px;
        border-radius: 20px;
        border: 1px solid #dcdfe6;
        margin-right: 20px;
        font-weight: bold;
    }

    .myticket:hover {
        cursor: pointer;
        color: #4c94a0;
        border: 1px solid rgba(76, 148, 160, 0.15);
        background-color: rgba(76, 148, 160, 0.15);
    }

    a {
        padding: 4px 8px 2px 8px;
    }

    /*.fa-ticket-alt {*/
        /*color: #526870;*/
    /*}*/
</style>