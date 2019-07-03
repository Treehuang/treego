<template>
    <div class="container">
        <light-timeline :items='items'>

            <template slot='tag' slot-scope='{ item }'>
                {{ item.created_at }}
            </template>

            <template slot='symbol' slot-scope='{ item }'>

            </template>

            <template slot='content' slot-scope='{ item }'>
                <div class="card">
                    <div class="card-body">
                        <span class="place">{{ item.start_place }} <i class="fas fa-arrow-right"></i> {{ item.terminus }}</span>
                        <i class="fas fa-ticket-alt"><span style="font-weight: normal"> {{ item.ticket_num }}</span></i>
                        <i class="fas fa-yen-sign"><span style="font-weight: normal; font-size: 14px"> {{ item.price }}</span></i>
                        <div class="clearfix"></div>
                        <span class="date"><i class="far fa-clock"></i> {{ item.date }} {{ item.from_time }}</span>
                        <span class="leader"><i class="far fa-user"></i> {{ username }}</span>
                    </div>

                    <div class="fare" v-show="item.state !== 0">
                        亲爱的同乡<span>{{ username }}</span>， 你好，这是一班从<span>{{ item.start_place }}</span>开往<span>广州大学{{ item.terminus }}校区</span>的班车，出发时间为
                        <span>{{ item.date }} {{ item.from_time }}</span>，请你在出发时间前到<span>{{ item.start_place }}</span>跟负责人<span>{{ item.leader }}</span>报道！
                    </div>
                </div>
            </template>
        </light-timeline>
    </div>

</template>

<script>

    import { mapState } from 'vuex';

    export default {

        name: 'TicketList',

        computed: {
            ...mapState({
                username : state => state.certification.username
            })
        },

        created() {
            $(function () { $("[data-toggle='tooltip']").tooltip(); });

            this.$api.ticket.getBuyTickets().then(response => {

                if (response.data.ticketList.length === 0) {

                    let today = new Date();
                    let kd = today.toLocaleString();
                    this.items = [
                        {
                            terminus: '暂无数据 ......',
                            created_at: kd,
                            state: 0,
                            ticket_num: 0,
                            date: '2019-05-05',
                            price: 0
                        }
                    ];
                }else {
                    // 当前时间戳
                    let nowTime = parseInt(new Date().getTime()/1000);

                    this.items = response.data.ticketList;
                    for (let i = 0, len = this.items.length; i < len; i++) {
                        // 出发时间
                        let dateTime = Date.parse(new Date(this.items[i].date + ' 11:59:59'))/1000;
                        if(this.items[i].state === 0 || nowTime > dateTime) {
                            this.items[i].color = '#dcdcdc';
                        }
                    }
                }

            }).catch(error => {

            });
        },

        data() {
            return {
                items: [],
                ticketList: '',
            }
        },
    }
</script>

<style scoped>
    .container {
        min-width: 1100px;
        padding: 0 110px 0 110px;
    }

    .card {
        width: 40%;
        background-color: #ffffff;
        border-radius: 6px;
        border: 1px solid rgba(139, 128, 249, 0.2);
        -webkit-box-shadow: -2px 2px 5px 2px rgba(80, 168, 180, 0.15);
        -moz-box-shadow: -2px 2px 5px 2px rgba(80, 168, 180, 0.15);
        -o-box-shadow: -2px 2px 5px 2px rgba(80, 168, 180, 0.15);
        box-shadow: -2px 2px 5px 2px rgba(139, 128, 249, 0.1);
    }

    .card-body {
        padding: 11px;
        position: relative;
    }

    .date, .leader {
        margin-right: 6px;
        border: 1px solid rgba(139, 128, 249, 0.1);
        border-radius: 3px;
        padding: 2px 6px 2px 6px;
        color: #45555d;
        font-size: 12px;
        background-color: rgba(139, 128, 249, 0.1);
    }

    .date:hover, .leader:hover {
        cursor: pointer;
    }

    .place {
        float: left;
        color: #4b8f9b;
        margin-bottom: 2px;
    }

    .fa-ticket-alt {
        margin-left: 15px;
        color: #68838b;
    }

    .fa-arrow-right {
        margin-left: 6px;
        margin-right: 6px;
    }

    .fare {
        top: 0;
        left: 340px;
        width: 420px;
        position: absolute;
        color: #45555d;
    }

    .fare > span {
        color: #4b8f9b;
    }

    .fa-yen-sign {
        color: #68838b;
        font-size: 13px;
        margin-left: 5px;
    }
</style>