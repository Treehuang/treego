<template>
    <div class="table">
        <div class="handle-box">

            <el-row :gutter="12">
                <div v-for="item in countFares">
                    <el-col  :span="4">
                        <el-card shadow="hover">
                            <span style="color: #45555d">{{ item.start_place }}</span>
                            <span style="float: right; color: #1ab394; font-weight: bold">票数 {{ item.ticket_num_sum }}</span>
                        </el-card>
                    </el-col>
                </div>
            </el-row>

        </div>

        <el-table border
                  show-summary
                  :data="countFares"
                  style="width: 100%">

            <el-table-column label="序号" type="index" width="50" align="center"></el-table-column>

            <el-table-column
                    prop="start_place"
                    label="出发地点"
                    align="center">
            </el-table-column>

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

            <el-table-column label="负责人" align="center" prop="leader"></el-table-column>

            <el-table-column label="总票数" align="center" prop="ticket_num_sum"></el-table-column>

        </el-table>
    </div>
</template>

<script>
    export default {
        name: 'StatisticalCenter',

        created() {
            this.$api.management_statistical.getCountFares().then(response => {
                this.countFares = response.data.data;
            })
        },

        data() {
            return {
                activeFares: [],
                faresIdList: [],
                start_place: '',
                countFares: [],
            }
        }

    }
</script>

<style scoped>
    .handle-box {
        margin-bottom: 20px;
    }

    .table {
        width: auto;
        margin: 20px 10px 10px 10px;
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

</style>