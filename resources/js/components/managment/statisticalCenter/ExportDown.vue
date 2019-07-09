<template>
    <div class="table">
        <div class="handle-box">
            <el-select v-model="start_place_id" placeholder="出发地点" size="mini" style="width: 130px">
                <el-option v-for="item in activeFares" :key="item.id" :label="item.start_place" :value="item.id"></el-option>
            </el-select>

            <span class="search" @click="search"><i class="el-icon-search"></i> 搜索</span>
            <span class="search" @click="clear">全部数据</span>

            <span class="search" @click="toUnOs(1)">大学城</span>
            <span class="search" @click="toUnOs(2)">桂花岗</span>

            <download-excel class = "excel_button" :data = "execl_data" :field = "json_fields" :name="excel_name">
                下载导出 <i class="el-icon-download"></i>
            </download-excel>
        </div>

        <el-table border :data="tableData" width="100%">
            <el-table-column label="序号" type="index" width="50" align="center"></el-table-column>

            <el-table-column
                    prop="start_place"
                    label="出发地点"
                    align="center">
            </el-table-column>

            <el-table-column
                    prop="terminus"
                    label="目的地"
                    align="center">
            </el-table-column>

            <el-table-column
                    prop="username"
                    label="订购者"
                    align="center">
            </el-table-column>

            <el-table-column
                    prop="ticket_num"
                    label="订购票数"
                    align="center">
            </el-table-column>

            <el-table-column
                    prop="buytime"
                    label="订购时间"
                    align="center">
            </el-table-column>

        </el-table>

        <!-- 分页 -->
        <div class="pagination">
            <el-pagination v-if="pageshow"
                    @current-change="handleCurrentChange"
                    background
                    :current-page="current_page"
                    layout="prev, pager, next"
                    :total="total_pages">
            </el-pagination>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ExportDown',

        created() {
            this.$api.management_statistical.getActiveFare().then(response => {
                this.activeFares = response.data.data;
            });

            this.$api.management_statistical.getAllBuyFares().then(response => {
                this.tableData = response.data.data;
                this.total_pages = response.data.meta.pagination.total_pages * 10;
            });

            // 要导出的excel数据
            this.$api.management_statistical.getExeclFares().then(response => {
                this.execl_data = response.data.data;
            })
        },

        data() {
            return {
                pageshow: true,

                toUnOsV: false,
                toUnOsSign: 0,

                excel_name: '全部数据.xls',
                execl_data: [],

                tableData: [],
                activeFares: [],
                start_place_id: '',

                total_pages: 0,
                current_page: 1,

                json_fields: {
                    '出发地点': 'start_place',
                    '目的地': 'terminus',
                    '订购者': 'username',
                    '订购票数': 'ticket_num',
                },
            }
        },

        methods: {
            handleCurrentChange(current_page) {

                // 记录此时的页码，供其他接口调
                this.current_page = current_page;

                if (!this.toUnOsV) {
                    // 无筛选条件
                    if (this.start_place_id === '') {
                        let formData = {
                            params: {
                                current_page: current_page,
                            }
                        };

                        this.$api.management_statistical.getCurrentBuyFares(formData).then(response => {
                            this.tableData = response.data.data;
                            this.total_pages = response.data.meta.pagination.total_pages * 10;
                        })
                    }else {
                        let formData = {
                            params: {
                                ticket_id: this.start_place_id,
                                current_page: current_page,
                            }
                        };

                        this.$api.management_statistical.getCurrentSearchFares(formData).then(response => {
                            this.tableData = response.data.data;
                            this.total_pages = response.data.meta.pagination.total_pages * 10;
                        })
                    }
                }else {
                    let formData = {};

                    if (this.toUnOsSign === 1) {
                        formData = {
                            params: {
                                terminus: '大学城',
                                current_page: current_page,
                            }
                        };
                    }else {
                        formData = {
                            params: {
                                terminus: '桂花岗',
                                current_page: current_page,
                            }
                        };
                    }

                    this.$api.management_statistical.getCurrentUnOsFare(formData).then(response => {
                        this.tableData = response.data.data;
                        this.total_pages = response.data.meta.pagination.total_pages * 10;
                    })
                }

            },

            search() {
                this.toUnOsV = false;
                if (this.start_place_id !== '') {
                    let formData = {
                        params: {
                            ticket_id: this.start_place_id,
                        }
                    };

                    this.$api.management_statistical.getSearchFares(formData).then(response => {
                        this.tableData = response.data.data;
                        this.total_pages = response.data.meta.pagination.total_pages * 10;

                        // 解决current-page 绑定的数据变了，但是页面当前页码并没有变的问题
                        this.current_page = 1;
                        this.pageshow = false;
                        this.$nextTick(() => {
                            this.pageshow = true
                        })
                    });

                    // 要导出的excel数据
                    this.$api.management_statistical.getExeclFare(formData).then(response => {
                        this.execl_data = response.data.data;
                        this.excel_name = response.data.data[0].start_place + '.xls';
                    })
                }
            },

            clear() {
                this.toUnOsV = false;
                this.start_place_id = '';
                this.$api.management_statistical.getAllBuyFares().then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;
                });

                this.current_page = 1;
                this.pageshow = false;
                this.$nextTick(() => {
                    this.pageshow = true
                });

                // 要导出的excel数据
                this.$api.management_statistical.getExeclFares().then(response => {
                    this.execl_data = response.data.data;
                    this.excel_name = '全部数据.xls';
                })
            },

            toUnOs(value) {
                this.toUnOsV = true;
                let formData = {};
                this.toUnOsSign = value;
                // 前往大学城
                if (value === 1) {
                    this.excel_name = '大学城.xls';
                    formData = {
                        params: {
                            terminus: '大学城',
                        }
                    }
                }else {
                    this.excel_name = '桂花岗.xls';
                    formData = {
                        params: {
                            terminus: '桂花岗',
                        }
                    }
                }

                this.$api.management_statistical.getUnOsFare(formData).then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

                    this.current_page = 1;
                    this.pageshow = false;
                    this.$nextTick(() => {
                        this.pageshow = true
                    })
                });

                this.$api.management_statistical.getUnOsFares(formData).then(response => {
                    this.execl_data = response.data.data;
                })
            }
        }
    }
</script>

<style scoped>
    .handle-box {
        margin-bottom: 10px;
    }

    .table {
        width: auto;
        margin: 20px 10px 10px 10px;
    }

    .el-select-dropdown__item.selected {
        color: #1ac9a9;
        font-weight: normal;
    }

    /deep/ input:focus, /deep/ .el-select .el-input.is-focus .el-input__inner {
        border: 1px solid #1ac9a9 !important;
    }
    .search {
        cursor: pointer;
        color: #ffffff;
        padding: 5px 12px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-left: 5px;
        margin-right: 5px;
    }

    .search:hover {
        background: #1ad2b2;
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

    .excel_button {
        cursor: pointer;
        color: #ffffff;
        padding: 3px 12px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        width: 90px;
        float: right;
        margin-top: 1px;
        margin-right: 2px;
    }

    .excel_button:hover {
        background: #1ad2b2;
    }
</style>