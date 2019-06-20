<template>
    <div class="card text-center">

        <div class="rectangleLeft"></div>
        <div class="rectangleRight"></div>

        <div class="card-body">
            <h5 class="card-title from_time">{{ ticketObject.from_time }}</h5>
            <h5 class="card-title arrival_time">{{ ticketObject.arrival_time }}</h5>
            <p class="card-text"><i class="fas fa-history"></i></p>
            <p class="card-text place">{{ ticketObject.start_place }}</p>
        </div>

        <hr>

        <div>
            <i class="fas fa-bus" v-if="sex !== 'f'"><span > have a nice trip</span></i>
            <i class="fas fa-heart" v-if="sex === 'f'"><span style="font-weight: normal; color: #45555d"> have a nice trip</span></i>

            <i class="fas fa-ticket-alt"><span class="ticketNum"> {{ ticketObject.ticket_num }}</span></i>

            <i class="far fa-minus-square" @click="minusTicket"></i>
            <i class="far fa-plus-square" @click="addTicket"></i>
        </div>

    </div>
</template>

<script>

    import { mapState } from 'vuex';

    export default {
        name: 'TheTicket',

        props: ['ticket'],

        computed: {
            ...mapState({
                sex: state => state.certification.sex,
            })
        },

        data() {
            return {
                ticketNum: 1,
                ticketObject: this.ticket,
            }
        },

        methods: {

            addTicket() {
                if (this.ticketObject.ticket_num === 4) {
                    return false;
                }

                this.ticketObject.ticket_num += 1;
            },

            minusTicket() {
                if (this.ticketObject.ticket_num === 1) {
                    return false;
                }else {
                    this.ticketObject.ticket_num -= 1;
                }
            },
        }
    }
</script>

<style scoped>

    .card {
        position: relative;
        background-color: #ffffff;
        border-radius: 6px;
        padding-bottom: 9px;
        border: 1px solid #fcfcfc;
        -webkit-box-shadow: -2px 2px 5px 2px rgba(80, 163, 175, 0.15);
        -moz-box-shadow: -2px 2px 5px 2px rgba(80, 163, 175, 0.15);
        -o-box-shadow: -2px 2px 5px 2px rgba(80, 163, 175, 0.15);
        box-shadow: -2px 2px 5px 2px rgba(80, 163, 175, 0.15);
    }

    .card-body:hover i {
        font-size: 18px;
    }

    hr {
        border-top: 1px solid rgb(222, 233, 234);
        margin-top: 0;
        margin-bottom: 10px;
    }

    .place {
        color: #596b73;
    }

    .card-title {
        color: #45555d;
    }

    .rectangleLeft {
        position: absolute;
        top: 22px;
        left: 0;
        width: 3px;
        height: 15px;
        background-color: #55a8b4;
        border: 1px solid #50a8b4;
    }

    .rectangleRight {
        position: absolute;
        top: 22px;
        right: 0;
        width: 3px;
        height: 15px;
        background-color: #f06694;
        border: 1px solid #f06496;
    }

    .from_time {
        float: left;
        margin-left: 20px
    }

    .arrival_time {
        float: right;
        margin-right: 20px
    }

    .fa-history {
        font-size: 16px;
        color: #54a3af;
    }

    .fa-heart {
        color: #f06694;
        float: left;
        width: 160px;
    }

    .fa-bus {
        color: #67838c;
        float: left;
        width: 160px;
    }

    .fa-ticket-alt {
        margin-left: 65px;
        color: #67838c;
        float: left;
    }

    .fa-minus-square {
        color: #50a8b4;
        float: right;
        margin-right: 15px;
    }

    .fa-minus-square:hover {
        color: #57bcc8;
    }

    .fa-plus-square {
        color: #50a8b4;
        float: right;
        margin-right: 15px
    }

    .fa-plus-square:hover {
        color: #57bcc8;
    }

    .ticketNum {
        font-size: 14px;
    }
</style>