<template>
    <div class="table" v-if="tableshow">
        <div class="handle-box">
            <el-input v-model="input" placeholder="筛选账号" size="mini" class="handle-input" style="margin-right: 10px"></el-input>
            <span class="common"><i class="el-icon-search"></i> 搜索</span>
            <span class="common" @click="getNoAudit"><i class="el-icon-thumb"></i> 待审核</span>
            <span class="common" @click="getAudit"><i class="el-icon-s-marketing"></i> 已审核</span>
            <span class="common" @click="getThrowAudit"><i class="el-icon-s-claim"></i> 通过审核</span>
            <span class="common" @click="getNoThrowAudit"><i class="el-icon-s-release"></i> 未通过审核</span>
        </div>

        <el-table
                border
                :data="tableData"
                style="width: 100%">

            <el-table-column
                    prop="realname"
                    label="真实性名"
                    align="center">
            </el-table-column>

            <el-table-column
                    prop="time"
                    label="入学年份"
                    align="center">
            </el-table-column>

            <el-table-column
                    label="学历"
                    align="center">
                <template slot-scope="scope">
                    <span v-if="scope.row.record === 'u'">
                        本科生
                    </span>
                    <span v-else>
                        研究生
                    </span>
                </template>
            </el-table-column>

            <el-table-column
                    v-if="show_audit_time"
                    prop="audit_time"
                    label="审核时间"
                    align="center">
            </el-table-column>

            <el-table-column
                    v-if="show_check_user"
                    prop="check_user"
                    label="审核者"
                    align="center">
            </el-table-column>

            <el-table-column
                    label="审核状态"
                    align="center">
                <template slot-scope="scope">
                    <el-tag v-if="scope.row.audit_state === 0"
                            type="danger"
                            disable-transitions>待审
                    </el-tag>
                    <el-tag v-else-if="scope.row.audit_state === 1"
                            type="success"
                            disable-transitions>通过
                    </el-tag>
                    <el-tag v-else
                            type="danger"
                            disable-transitions>不通过
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column label="学籍证明" align="center">
                <template slot-scope="scope">
                    <span class="look" @click="check(scope.row.certificat)">
                        <i class="el-icon-search"> 查看</i>
                    </span>
                </template>
            </el-table-column>

            <el-table-column
                    v-if="show_reason"
                    label="原因"
                    align="center">
                <template slot-scope="scope">
                    <span v-if="scope.row.audit_state === 1">
                        /
                    </span>
                    <span v-else class="look" @click="noThrowAudit(scope)">
                        <i class="el-icon-search"> 查看</i>
                    </span>
                </template>
            </el-table-column>

            <el-table-column
                    v-if="show_operate"
                    label="操作"
                    width="300"
                    align="center">
                <template slot-scope="scope">
                    <span class="pass" @click="throwAudit(scope)">
                        <i class="el-icon-check"> 可以通过</i>
                    </span>
                    <span class="noPass" @click="noThrowAudit(scope)">
                        <i class="el-icon-close"> 不能通过</i>
                    </span>
                </template>

            </el-table-column>

        </el-table>

        <!-- 分页 -->
        <div class="pagination">
            <el-pagination
                    v-if="pageshow"
                    @current-change="handleCurrentChange"
                    background
                    :current-page="current_page"
                    layout="prev, pager, next"
                    :total="total_pages">
            </el-pagination>
        </div>

        <!-- 显示学籍证明 -->
        <el-dialog title="学籍证明" :visible.sync="certificatVisible" width="350px" :show-close="showClose" :close-on-click-modal="close_on_click_modal" center>

            <div class="demo-image">
                <div class="block">
                    <el-image
                            style="width: 300px; height: 300px"
                            :src="imageUrl"
                            :fit="cover">
                    </el-image>
                </div>
            </div>

            <span slot="footer" class="dialog-footer">
                <span class="cancel" @click="certificatVisible = false">取 消</span>
                <span class="sure">确 定</span>
            </span>
        </el-dialog>

        <!-- 无法通过的原因填写 -->
        <el-dialog title="无法通过的原因" :visible.sync="noPassVisible" width="450px" :show-close="showClose" :close-on-click-modal="close_on_click_modal" center>

            <div v-if="this.user.audit_state === 0">
                <textarea v-validate="'noPassReason'" :class="[{ 'is-invalid' : errors.has('noPassReason') }, 'form-control']" name="noPassReason" rows="3" v-model="noPassReason" placeholder="可填可不填..."></textarea>
                <span class="invalid-feedback error-message" v-if="errors.has('noPassReason')">{{ errors.first('noPassReason') }}</span>
            </div>
           <span v-else style="color: #45555d; font-weight: bold">{{ reason }}</span>

            <span slot="footer" class="dialog-footer">
                <span class="cancel" @click="noPassVisible = false">取 消</span>
                <span v-if="this.user.audit_state === 0" class="sure" @click="noPass">确 定</span>
                <span v-else class="sure" @click="noPassVisible = false">确 定</span>
            </span>
        </el-dialog>
    </div>
</template>

<script>

    import bus from '../../../bus/bus';

    export default {
        name: 'certificatCheck',

        created() {
            this.getNoAuditNum();

            // 获取待审核学籍证明
            this.$api.management_audit.getNoAudits().then(response => {
                this.no_audit_num = response.data.data.length;
            })
        },

        data() {
            return{
                user: '',
                input: '',
                tableData: [],

                imageUrl: '',
                cover: 'cover',

                pageshow: true,
                tableshow: true,
                total_pages: 0,
                current_page: 1,

                showClose: false,

                show_audit_time: false,
                show_check_user: false,
                show_operate: true,
                show_reason: false,

                noPassVisible: false,
                certificatVisible: false,

                no_audit_num: 0,

                no_audit: true,
                audit: false,
                through_audit: false,
                unthrough_audit: false,

                reason: '',
                noPassReason: '',
                close_on_click_modal: false
            }
        },

        methods: {
            // 获取待审核数
            getNoAuditNum() {
                this.$api.management_audit.getNoAudit().then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;
                })
            },

            check(imageUrl) {
                this.certificatVisible = true;
                this.imageUrl = imageUrl;
            },

            handleCurrentChange(current_page) {
                // 记录此时的页码，供其他接口调
                this.current_page = current_page;

                let formData = {
                    params: {
                        current_page: current_page,
                    }
                };

                // 当前为已审核
                if (this.audit) {
                    this.$api.management_audit.getCurrentPagesAudit(formData).then(response => {
                        this.tableData = response.data.data;
                        this.total_pages = response.data.meta.pagination.total_pages * 10;
                    })
                }

                // 当前为待审核
                if (this.no_audit) {
                    this.$api.management_audit.getCurrentPagesNoAudit(formData).then(response => {
                        this.tableData = response.data.data;
                        this.total_pages = response.data.meta.pagination.total_pages * 10;
                    })
                }

                // 当前为通过审核
                if (this.through_audit) {
                    this.$api.management_audit.getCurrentPagesThrowAudit(formData).then(response => {
                        this.tableData = response.data.data;
                        this.total_pages = response.data.meta.pagination.total_pages * 10;
                    })
                }

                // 当前为未通过审核
                if (this.unthrough_audit) {
                    this.$api.management_audit.getCurrentPagesNoThrowAudit(formData).then(response => {
                        this.tableData = response.data.data;
                        this.total_pages = response.data.meta.pagination.total_pages * 10;
                    })
                }
            },

            // 待审核按钮
            getNoAudit() {
                this.$api.management_audit.getNoAudit().then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

                    this.audit = false;
                    this.no_audit = true;
                    this.through_audit = false;
                    this.unthrough_audit = false;

                    this.show_audit_time = false;
                    this.show_check_user =  false;
                    this.show_operate = true;
                    this.show_reason = false;

                    this.tableshow = false;
                    this.$nextTick(() => {
                        this.tableshow = true
                    });

                    // 解决current-page 绑定的数据变了，但是页面当前页码并没有变的问题
                    this.current_page = 1;
                    this.pageshow = false;
                    this.$nextTick(() => {
                        this.pageshow = true
                    })
                })
            },

            // 管理员通过审核
            throwAudit(user) {
                let formData = {
                    id: user.row.id,
                    user_id: user.row.user_id,
                    current_page: this.current_page,
                };

                this.$api.management_audit.throwAudit(formData).then(response => {
                    this.tableData = response.data.data;

                    this.total_pages = response.data.meta.pagination.total_pages * 10;
                    this.no_audit_num = this.no_audit_num - 1;
                    bus.$emit('get_no_audit_num', this.no_audit_num);

                    // 解决不断审核最后一页后跳到上一页显示无数据的问题
                     if (this.tableData.length === 0) {

                         if (this.current_page !== 1) {
                             this.current_page = this.current_page - 1;

                             let formData = {
                                 params: {
                                     current_page: this.current_page,
                                 }
                             };
                             this.$api.management_audit.getCurrentPagesNoAudit(formData).then(response => {
                                 this.tableData = response.data.data;
                                 this.total_pages = response.data.meta.pagination.total_pages * 10;
                             })
                         }
                    }

                    this.$swal.fire({
                        type: 'success',
                        title: '操作成功~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then();
                })
            },

            // 管理员审核不通过
            noThrowAudit(user) {
                // 审核不通过界面的查看原因按钮
                if (user.row.audit_state === 2) {
                    let formData = {
                        params: {
                            id: user.row.id,
                            user_id: user.row.user_id,
                            updated_at: user.row.audit_time,
                        }
                    };

                    this.$api.management_audit.getNoPassReason(formData).then(response => {
                        this.reason = response.data.data;
                    })
                }

                this.noPassReason = '';
                this.noPassVisible = true;
                this.user = user.row;
            },

            noPass() {
                this.$validator.validate('noPassReason').then((result) => {
                    if (result) {

                        this.noPassVisible = false;

                        let formData = {
                            id: this.user.id,
                            user_id: this.user.user_id,
                            noPassReason: this.noPassReason,
                            current_page: this.current_page,
                        };

                        this.$api.management_audit.noThrowAudit(formData).then(response => {
                            this.tableData = response.data.data;

                            this.total_pages = response.data.meta.pagination.total_pages * 10;
                            this.no_audit_num = this.no_audit_num - 1;
                            bus.$emit('get_no_audit_num', this.no_audit_num);

                            // 解决不断审核最后一页后跳到上一页显示无数据的问题
                            if (this.tableData.length === 0) {

                                if (this.current_page !== 1) {
                                    this.current_page = this.current_page - 1;

                                    let formData = {
                                        params: {
                                            current_page: this.current_page,
                                        }
                                    };
                                    this.$api.management_audit.getCurrentPagesNoAudit(formData).then(response => {
                                        this.tableData = response.data.data;
                                        this.total_pages = response.data.meta.pagination.total_pages * 10;
                                    })
                                }
                            }

                            this.$swal.fire({
                                type: 'success',
                                title: '操作成功~',
                                toast: true,
                                position: 'top',
                                showConfirmButton: false,
                                timer: 2500,
                            }).then();
                        })
                    }
                })
            },

            // 已审核按钮（通过和未通过）
            getAudit() {
                this.$api.management_audit.getAudit().then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

                    this.audit = true;
                    this.no_audit = false;
                    this.through_audit = false;
                    this.unthrough_audit = false;

                    this.show_audit_time = true;
                    this.show_check_user =  true;
                    this.show_reason = true;
                    this.show_operate = false;

                    this.tableshow = false;
                    this.$nextTick(() => {
                        this.tableshow = true
                    });

                    // 解决current-page 绑定的数据变了，但是页面当前页码并没有变的问题
                    this.current_page = 1;
                    this.pageshow = false;
                    this.$nextTick(() => {
                        this.pageshow = true
                    })
                })
            },

            // 通过审核按钮
            getThrowAudit() {
                this.$api.management_audit.getThrowAudit().then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

                    this.audit = false;
                    this.no_audit = false;
                    this.through_audit = true;
                    this.unthrough_audit = false;

                    this.show_audit_time = true;
                    this.show_check_user =  true;
                    this.show_reason = true;
                    this.show_operate = false;

                    this.tableshow = false;
                    this.$nextTick(() => {
                        this.tableshow = true
                    });

                    // 解决current-page 绑定的数据变了，但是页面当前页码并没有变的问题
                    this.current_page = 1;
                    this.pageshow = false;
                    this.$nextTick(() => {
                        this.pageshow = true
                    })
                })
            },

            // 未通过审核按钮
            getNoThrowAudit() {
                this.$api.management_audit.getNoThrowAudit().then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

                    this.audit = false;
                    this.no_audit = false;
                    this.through_audit = false;
                    this.unthrough_audit = true;

                    this.show_audit_time = true;
                    this.show_check_user =  true;
                    this.show_reason = true;
                    this.show_operate = false;

                    this.tableshow = false;
                    this.$nextTick(() => {
                        this.tableshow = true
                    });

                    // 解决current-page 绑定的数据变了，但是页面当前页码并没有变的问题
                    this.current_page = 1;
                    this.pageshow = false;
                    this.$nextTick(() => {
                        this.pageshow = true
                    })
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

    /deep/ .el-table {
        border-top: 0;
        border-right: 1px solid #dee2e6;
        border-left: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        width: auto;
        border-radius: 4px;
    }

    .common {
        cursor: pointer;
        color: #ffffff;
        padding: 5px 12px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-right: 10px;
        line-height: 28px;
    }

    .common:hover {
        background: #1ad2b2;
    }

    .look {
        cursor: pointer;
        color: #ffffff;
        padding: 6px 12px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        line-height: 28px;
    }

    .look:hover {
        background: #1ad2b2;
    }

    .common:hover {
        background: #1ad2b2;
    }

    .pass {
        cursor: pointer;
        color: #ffffff;
        padding: 6px 12px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-right: 8px;
        line-height: 28px;
    }

    .pass:hover {
        background: #1ad2b2;
    }

    .noPass {
        cursor: pointer;
        color: #ffffff;
        padding: 6px 12px;
        background: #f56c6c;
        border: 1px solid #f56c6c !important;
        border-radius: 3px;
        font-size: 12px;
        margin-left: 8px;
        line-height: 28px;
    }

    .noPass:hover {
        background-color: #ff6f6b;
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

    /deep/ .el-dialog--center .el-dialog__body {
        text-align: initial;
        padding: 10px 25px 10px 25px;
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

    /deep/ input:focus, /deep/ .el-select .el-input.is-focus .el-input__inner, textarea:focus {
        border: 1px solid #1ac9a9 !important;
    }

    .handle-input {
        width: 150px;
        display: inline-block;
    }
</style>