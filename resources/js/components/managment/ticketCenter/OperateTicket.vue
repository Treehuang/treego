<template>
    <div class="table">
        <div class="handle-box">
            <span style="font-weight: bold; color: #45555d; margin-right: 10px">设置Ticket Office</span>
            <el-tooltip :content="switch_message" placement="top">
                <el-switch
                        v-model="value"
                        @click.native="change"
                        disabled
                        active-color="#009688"
                        inactive-color="#9c9fa5">
                </el-switch>
            </el-tooltip>

        </div>

        <el-table border :data="tableData" style="width: 100%" :row-class-name="tableRowClassName">
            <el-table-column label="出发地点" align="center" prop="start_place"></el-table-column>

            <el-table-column label="出发日期" align="center">
                <template slot-scope="scope">
                    <i class="el-icon-date"> {{ scope.row.date }}</i>
                </template>
            </el-table-column>

            <el-table-column label="出发时间" align="center">
                <template slot-scope="scope">
                    <i class="el-icon-time"> {{ scope.row.from_time }}</i>
                </template>
            </el-table-column>

            <el-table-column label="抵达时间" align="center">
                <template slot-scope="scope">
                    <i class="el-icon-time"> {{ scope.row.arrival_time }}</i>
                </template>
            </el-table-column>

            <el-table-column label="票价" align="center" prop="price"></el-table-column>

            <el-table-column label="负责人" align="center" prop="leader"></el-table-column>

            <el-table-column label="状态" width="110px" align="center">
                <template slot-scope="scope">
                    <el-tag v-if="scope.row.state === 1"
                            type="success"
                            disable-transitions>已激活
                    </el-tag>
                    <el-tag v-else
                            type="info"
                            disable-transitions>未激活
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column label="操作" width="250" align="center">
                <template slot-scope="scope">

                    <span class="activation" v-if="scope.row.state === 0" @click="activeFare(scope)">
                       <i class="el-icon-lock" > 激活</i>
                    </span>
                    <span v-else class="unactivation" @click="unActiveFare(scope)">
                        <i class="el-icon-unlock" > 失效</i>
                    </span>

                    <span class="remove" @click="unsets(scope)"><i class="el-icon-delete"> 删除</i></span>

                </template>
            </el-table-column>

        </el-table>

        <!-- 分页 -->
        <div class="pagination">
            <el-pagination
                    @current-change="handleCurrentChange"
                    background
                    :current-page="current_page"
                    layout="prev, pager, next"
                    :total="total_pages">
            </el-pagination>
        </div>

        <!-- 删除单个账号提示框 -->
        <el-dialog title="提示" :visible.sync="delVisible" width="300px" :show-close="showClose" center>
            <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
            <span slot="footer" class="dialog-footer">
                <span class="cancel" @click="delVisible = false">取 消</span>
                <span class="sure" @click="deleteFare">确 定</span>
            </span>
        </el-dialog>

        <!-- 开启 Ticket Office 提示框 -->
        <el-dialog title="开启 Ticket Office 是否要发送系统通知？" :visible.sync="ticketVisibleOne" width="380px" :show-close="showClose" center>
            <div class="del-dialog-cnt">
                <el-radio v-model="send" label="1">发送</el-radio>
                <el-radio v-model="send" label="0">不发送</el-radio>
            </div>
            <span slot="footer" class="dialog-footer">
                <span class="cancel" @click="ticketVisibleOne = false">取 消</span>
                <span class="sure" @click="openTicketOffice">确 定</span>
            </span>
        </el-dialog>

        <!-- 关闭 Ticket Office 提示框 -->
        <el-dialog title="提示" :visible.sync="ticketVisibleTwo" width="300px" :show-close="showClose" center>
            <div class="del-dialog-cnt">
                <div class="del-dialog-cnt">确认关闭 Ticket Office ？</div>
            </div>
            <span slot="footer" class="dialog-footer">
                <span class="cancel" @click="ticketVisibleTwo = false">取 消</span>
                <span class="sure" @click="closeTicketOffice">确 定</span>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    import bus from '../../../bus/bus'

    export default {
        name: 'OperateTicket',

        created() {
            // 获取车票
            this.$api.management_fare.getAllFares().then(response => {
                this.tableData = response.data.data;
                this.total_pages = response.data.meta.pagination.total_pages * 10;
            });

            // 获取车票系统是否开启
            this.$api.ticket.getTicketsOfficeState().then(response => {
                let ticketsOfficeState = response.data.ticketsOfficeState;
                if (ticketsOfficeState === 1) {
                    this.value = true;
                    this.switch_message = '关闭 Ticket Office';
                }else {
                    this.value = false;
                    this.switch_message = '开启 Ticket Office';
                }
            });
        },

        data() {
            return {
                send: '1',

                activeFares: [],

                value: false,
                switch_message: '',

                fare: '',
                tableData: [],
                total_pages: 0,
                current_page: 1,

                showClose: false,
                delVisible: false,
                ticketVisibleOne: false,
                ticketVisibleTwo: false,
            }
        },

        methods: {

            unsets(scope) {
                this.delVisible = true;
                this.fare = scope.row;
            },

            deleteFare() {
                let formData = {
                    params: {
                        id: this.fare.id,
                        current_page: this.current_page,
                    }
                };

                this.$api.management_fare.deleteFare(formData).then(response => {
                    this.delVisible = false;
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

                    // 解决不断删除这一页后跳到上一页显示无数据
                    if (this.tableData.length === 0) {
                        if (this.current_page !== 1) {
                            this.current_page = this.current_page - 1;
                            this.handleCurrentChange(this.current_page);
                        }
                    }

                    this.$swal.fire({
                        type: 'success',
                        title: '删除成功~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                });
            },

            // 分页导航
            handleCurrentChange(current_page) {
                // 记录此时的页码，供其他接口调
                this.current_page = current_page;

                let formData = {
                    params: {
                        current_page: current_page,
                    }
                };

                this.$api.management_fare.getCurrentFares(formData).then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;
                })
            },

            // 改变表格行的背景色
            tableRowClassName(rowData) {
                // 激活状态
                if (rowData.row.state === 1) {
                    return 'success-row';
                }else {
                    return '';
                }
            },

            // 激活车票
            activeFare(fare) {
                let formData = { id: fare.row.id };

                this.$api.management_fare.activeFare(formData).then(() => {
                    fare.row.state = 1;
                    this.$swal.fire({
                        type: 'success',
                        title: '激活成功~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                })
            },

            // 使车票失效
            unActiveFare(fare) {
                let formData = { id: fare.row.id };

                this.$api.management_fare.unActiveFare(formData).then(() => {
                    fare.row.state = 0;
                    this.$swal.fire({
                        type: 'success',
                        title: '失效成功~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                })
            },

            change() {
                if (this.value) {
                    this.ticketVisibleTwo = true;
                }else {
                    this.activeFares = [];
                    // 检测到没有激活的车票，不给开启
                    for (let i = 0, length = this.tableData.length; i < length; i++) {
                        if (this.tableData[i].state !== 0) {
                            this.activeFares.push(this.tableData[i]);
                        }
                    }

                    if (this.activeFares.length === 0) {
                        this.$swal.fire({
                            type: 'warning',
                            title: '请先激活需要上架的ticket~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        });

                        return false;
                    }

                    this.ticketVisibleOne = true;
                }
            },

            // 开启 Ticekt Office
            openTicketOffice() {
                let formData = {};
                if (this.send === '1') {
                    formData = { state: 1, send: 1 };
                }else {
                    formData = { state: 1, send: 0 };
                }

                this.$api.management_fare.setTicketOffice(formData).then(() => {
                    this.value = true;
                    this.ticketVisibleOne = false;
                    bus.$emit('get_ticket_state', 1);
                    this.switch_message = '关闭 Ticket Office';
                    this.$swal.fire({
                        type: 'success',
                        title: '开启成功~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    })
                });
            },

            closeTicketOffice() {
                let formData = { state: 0 };
                this.$api.management_fare.setTicketOffice(formData).then(() => {
                    this.value = false;
                    this.ticketVisibleTwo = false;
                    bus.$emit('get_ticket_state', 0);
                    this.switch_message = '开启 Ticket Office';
                    this.$swal.fire({
                        type: 'success',
                        title: '关闭成功~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    })
                });
            }
        },
    }
</script>

<style scoped>
    .table {
        width: auto;
        margin: 20px 10px 10px 10px;
    }

    .handle-box {
        margin-left: 2px;
        margin-bottom: 10px;
    }

    /deep/ .el-table {
        border-top: 0;
        border-right: 1px solid #dee2e6;
        border-left: 1px solid #dee2e6;
        border-bottom: 0;
        width: auto;
        border-radius: 4px;
    }

    /deep/ .el-table td, /deep/ .el-table th.is-leaf {
        border-bottom: 0;
    }

    .unactivation {
        cursor: pointer;
        color: #E6A23C;
        padding: 6px 12px;
        background: #fdf6ec;
        border: 1px solid #f5dab1 !important;
        border-radius: 3px;
        font-size: 12px;
        line-height: 28px;
        margin-right: 10px;
    }

    .unactivation:hover {
        color: #ffffff;
        background: #f5af3f;
        border: 1px solid #f5af3f !important;
    }

    .activation {
        cursor: pointer;
        color: #67c23a;
        padding: 6px 12px;
        background: #f0f9eb;
        border: 1px solid #e1f3d8 !important;
        border-radius: 3px;
        font-size: 12px;
        line-height: 28px;
        margin-right: 10px;
    }

    .activation:hover {
        color: #ffffff;
        background: #6dc83a;
        border: 1px solid #69c83c !important;
    }

    .remove {
        cursor: pointer;
        color: #F56C6C;
        padding: 6px 12px;
        background: #fef0f0;
        border: 1px solid #fbc4c4 !important;
        border-radius: 3px;
        font-size: 12px;
        line-height: 28px;
        margin-right: 10px;
    }

    .remove:hover {
        color: #ffffff;
        background: #f56c6c;
        border: 1px solid #f56c6c !important;
    }

    .pagination {
        float: right;
        margin-top: 10px;
    }

    /deep/ .el-pagination.is-background .el-pager li:not(.disabled):hover {
        color: #1ac9a9;
    }

    /deep/ .el-pagination.is-background .el-pager li:not(.disabled).active {
        background-color: #1ac9a9;
        color: #ffffff;
    }

    /deep/ .el-pagination.is-background .el-pager li, /deep/ .el-pagination.is-background .btn-next, /deep/ .el-pagination.is-background .btn-prev {
        background-color: rgba(240, 249, 235, 0.1);
    }

    .del-dialog-cnt{
        font-size: 16px;
        text-align: center
    }

    .sure {
        cursor: pointer;
        color: #ffffff;
        padding: 7px 14px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-left: 8px;
    }

    .sure:hover {
        background: #1ad2b2;
    }

    .cancel {
        cursor: pointer;
        color: #ffffff;
        padding: 7px 14px;
        background: #b4b4b4;
        border: 1px solid #b4b4b4 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-right: 8px;
        margin-left: 4px;
    }

    .cancel:hover {
        background: #aaaaaa;
    }

    /deep/ .el-table .success-row {
        background: #f0f9eb;
    }

    /deep/ .el-radio__input.is-checked .el-radio__inner {
        border-color: #1ac9a9;
        background: #1ac9a9;
    }

    /deep/ .el-radio__inner:hover {
        border-color: #1ac9a9;
    }

    /deep/ .el-radio__input.is-checked+.el-radio__label {
        color: #1ac9a9;
    }

    /deep/ .el-switch.is-disabled .el-switch__core, .el-switch.is-disabled .el-switch__label{
        cursor: pointer;
    }
</style>