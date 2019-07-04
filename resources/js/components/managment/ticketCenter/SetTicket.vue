<template>
    <div class="table">
        <!--<button class="btn btn-primary" @click="test">测试</button>-->

        <div class="handle-box">
            <span class="sure" @click="save">提交</span>
        </div>

        <el-table border stripe :data="tableData" style="width: 100%">

            <el-table-column label="序号" type="index" width="50" align="center"></el-table-column>

            <el-table-column label="出发地点" align="center">
                <template slot-scope="scope">
                    <el-input v-model="scope.row.start_place"></el-input>
                </template>
            </el-table-column>

            <el-table-column label="出发日期" align="center">
                <template slot-scope="scope">
                    <el-input prefix-icon="el-icon-date" v-model="scope.row.date" :id="scope.row.date_id" type="text" @focus="getDate(scope.row)"></el-input>
                </template>
            </el-table-column>

            <el-table-column label="出发时间" align="center">
                <template slot-scope="scope">
                    <el-input prefix-icon="el-icon-time" v-model="scope.row.from_time" :id="scope.row.from_time_id" type="text" @focus="getFromTime(scope.row)"></el-input>
                </template>
            </el-table-column>

            <el-table-column label="抵达时间" align="center">
                <template slot-scope="scope">
                    <el-input prefix-icon="el-icon-time" v-model="scope.row.arrival_time" :id="scope.row.arrival_time_id" type="text" @focus="getArrivalTime(scope.row)"></el-input>
                </template>
            </el-table-column>

            <el-table-column label="票价" align="center">
                <template slot-scope="scope">
                    <el-input v-model="scope.row.price"></el-input>
                </template>
            </el-table-column>

            <el-table-column label="负责人" align="center">
                <template slot-scope="scope">
                    <el-input v-model="scope.row.leader"></el-input>
                </template>
            </el-table-column>

            <el-table-column label="操作" align="center">
                <template slot-scope="scope">
                    <i class="el-icon-circle-plus add" @click="addRow"></i>
                    <i class="el-icon-remove reduce" @click="delRow(scope.$index)"></i>
                </template>
            </el-table-column>

        </el-table>
    </div>
</template>

<script>

    export default {
        name: 'SetTicket',

        data() {
            return {
                dates: '',
                tableData: [
                    {
                        date: '',
                        date_id: 'a',
                        from_time: '',
                        from_time_id: 'b',
                        arrival_time: '',
                        arrival_time_id: 'c',
                        start_place: '',
                        leader: '',
                        price: '',
                    },
                ],
            }
        },

        methods: {
            test() {
                console.log(this.tableData);
            },

            // 获取随机 dom id
            getRandomId() {
                let chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                let result = '';
                for (let i = 10; i > 0; i--) {
                    result += chars[Math.floor(Math.random() * chars.length)];
                }
                return result;
            },

            getDate(row) {
                laydate.render({
                    elem: '#' + row.date_id,
                    trigger: 'click',
                    done: (value) => {
                        row.date = value
                    }
                })
            },

            getFromTime(row) {
                laydate.render({
                    elem: '#' + row.from_time_id,
                    trigger: 'click',
                    type: 'time',
                    done: (value) => {
                        row.from_time = value
                    }
                })
            },

            getArrivalTime(row) {
                laydate.render({
                    elem: '#' + row.arrival_time_id,
                    trigger: 'click',
                    type: 'time',
                    done: (value) => {
                        row.arrival_time = value
                    }
                })
            },

            addRow() {
                let date_id = this.getRandomId();
                let from_time_id = this.getRandomId();
                let arrival_time_id = this.getRandomId();

                let list = {
                    date: '',
                    date_id: date_id,
                    from_time: '',
                    from_time_id: from_time_id,
                    arrival_time: '',
                    arrival_time_id: arrival_time_id,
                    start_place: '',
                    leader: '',
                    price: '',
                };

                this.tableData.push(list);
            },

            delRow(index) {
                if (index == 0) {
                    this.$swal.fire({
                        type: 'warning',
                        title: '无法删除此行~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then(); return false;
                }

                this.tableData.splice(index, 1);
            },

            save() {

                for (let i = 0, length = this.tableData.length; i < length; i++) {
                    let row = this.tableData[i];
                    if (row.leader === '' || row.date === '' || row.price === '' || row.from_time === '' || row.arrival_time === '' || row.start_place === '') {
                        this.$swal.fire({
                            type: 'warning',
                            title: '请完善车票信息~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        }).then();

                        return false;
                    }
                }

                let formData = {
                    faresList: this.tableData,
                }

                this.$api.management_fare.addFares(formData).then(() => {
                    this.$swal.fire({
                        type: 'success',
                        title: '提交成功~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();

                    this.tableData = [
                        {
                            date: '',
                            date_id: 'a',
                            from_time: '',
                            from_time_id: 'b',
                            arrival_time: '',
                            arrival_time_id: 'c',
                            start_place: '',
                            leader: '',
                            price: '',
                        },
                    ]
                })
            }
        }
    }
</script>

<style scoped>
    .table {
        width: auto;
        margin: 20px 10px 10px 10px;
    }

    .handle-box {
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

    .add {
        cursor: pointer;
        color: #1ac9a9;
        font-size: 22px;
        margin-right: 10px;
    }

    .add:hover {
        color: #1ad2b2;
    }

    .reduce {
        cursor: pointer;
        color: #f56c6c;
        font-size: 22px;
    }

    .reduce:hover {
        color: #ff6f6b;
    }

    .sure {
        cursor: pointer;
        color: #ffffff;
        padding: 5px 12px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-left: 1px;
    }

    .sure:hover {
        background: #1ad2b2;
    }
</style>