<template>
    <div class="table">

        <div class="handle-box">

            <el-select v-model="state" placeholder="状态" size="mini" style="width: 80px">
                <el-option key="1" label="正常" value="1"></el-option>
                <el-option key="2" label="冻结" value="2"></el-option>
            </el-select>
            <el-select v-model="is_check" placeholder="学籍认证" size="mini" style="width: 110px;">
                <el-option key="1" label="已认证" value="1"></el-option>
                <el-option key="2" label="未认证" value="2"></el-option>
            </el-select>

            <el-input v-model="input" placeholder="筛选用户名" size="mini" class="handle-input" style="margin-right: 10px"></el-input>
            <span class="search" @click="search"><i class="el-icon-search"></i> 搜索</span>
            <span class="search" @click="clear"><i class="el-icon-magic-stick"></i> 清除</span>
            <span class="search" @click="refresh"><i class="el-icon-refresh"></i> 刷新</span>
            <span class="removeAll" @click="batchUnset"><i class="el-icon-delete"></i> 批量删除</span>
        </div>

        <el-table
                ref="multipleTable"
                border
                :data="tableData"
                style="width: 100%"
                :row-class-name="tableRowClassName"
                @selection-change="handleSelectionChange">

            <el-table-column type="selection" width="55"></el-table-column>

            <el-table-column
                    label="日期"
                    align="center">
                <template slot-scope="scope"><i class="el-icon-time"></i><span style="margin-left: 10px">{{ scope.row.created_at }}</span></template>
            </el-table-column>

            <el-table-column
                    prop="username"
                    label="用户名"
                    align="center">
            </el-table-column>

            <el-table-column
                    label="校区"
                    align="center">
                <template slot-scope="scope">
                    <span v-if="scope.row.campus === 'b'">大学城</span>
                    <span v-else>桂花岗</span>
                </template>
            </el-table-column>

            <el-table-column
                    prop="college"
                    label="学院"
                    width="170px"
                    align="center">
            </el-table-column>

            <el-table-column
                    label="角色"
                    align="center">
                <template slot-scope="scope">
                    <el-tag v-if="scope.row.is_manager === 1 && scope.row.id !== 1"
                            type="success"
                            disable-transitions>管理员
                    </el-tag>
                    <el-tag v-else-if="scope.row.id === 1"
                            type="success"
                            disable-transitions>超级管理员
                    </el-tag>
                    <el-tag v-else
                            type="info"
                            disable-transitions>普通者
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column
                    label="状态"
                    align="center">
                <template slot-scope="scope">
                    <el-tag v-if="scope.row.state === 1"
                            type="success"
                            disable-transitions>正常
                    </el-tag>
                    <el-tag v-else
                            type="warning"
                            disable-transitions>冻结
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column
                    label="学籍认证"
                    align="center">
                <template slot-scope="scope">
                    <el-tag v-if="scope.row.is_check === 1"
                            type="success"
                            disable-transitions>已认证
                    </el-tag>
                    <el-tag v-else
                            type="info"
                            disable-transitions>未认证
                    </el-tag>
                </template>
            </el-table-column>

            <el-table-column label="操作" width="300" align="center">
                <template slot-scope="scope">

                    <span class="freeze" v-if="scope.row.state === 1" @click="freeze(scope)">
                        <i class="el-icon-lock" > 冻结</i>
                    </span>
                    <span v-else class="unfreeze" @click="unfreeze(scope)">
                        <i class="el-icon-unlock" > 解冻</i>
                    </span>

                    <span class="remove" @click="unsets(scope)"><i class="el-icon-delete"> 删除</i></span>

                    <span class="authorization" v-if="scope.row.is_manager === 0" @click="authorization(scope)">
                        <i class="el-icon-folder-checked"> 授权</i>
                    </span>
                    <span class="authorization" v-else @click="unAuthorization(scope)">
                        <i class="el-icon-folder-delete"> 回收</i>
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

        <!-- 删除单个账号提示框 -->
        <el-dialog title="提示" :visible.sync="delVisible" width="300px" :show-close="showClose" center>
            <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
            <span slot="footer" class="dialog-footer">
                <span class="cancel" @click="delVisible = false">取 消</span>
                <span class="sure" @click="deleteUser">确 定</span>
            </span>
        </el-dialog>

        <!-- 批量删除账号提示框 -->
        <el-dialog title="提示" :visible.sync="batchDelVisible" width="300px" :show-close="showClose" center>
            <div class="del-dialog-cnt">删除不可恢复，是否确定删除？</div>
            <span slot="footer" class="dialog-footer">
                <span class="cancel" @click="batchDelVisible = false">取 消</span>
                <span class="sure" @click="batcheleteUser">确 定</span>
            </span>
        </el-dialog>

    </div>
</template>

<script>
    export default {
        name: 'mAccount',

        created() {
            // 获取所有账号
            this.$api.management_account.getUsers().then(response => {
                this.tableData = response.data.data;
                this.total_pages = response.data.meta.pagination.total_pages * 10;
            });

            // 获取该用户的id
            this.$api.management_account.getTopManagemerId().then(response => {
                this.id = response.data.id;
            })
        },

        data() {
            return {
                id: '',
                user: '',
                state: '',
                input: '',
                is_check: '',
                pageshow: true,
                showClose: false,
                total_pages: 0,
                current_page: 1,
                delVisible: false,
                batchDelVisible: false,
                selectList: [],
                selectIdList: [],
                selectManagerList: [],
                tableData: [],
                multipleSelection: []
            }
        },

        methods: {
            // 批量选择
            handleSelectionChange(val) {
                this.selectList = val;
                // 记录选中的账号id
                this.selectIdList = [];     // 防止出现重复数据
                this.selectManagerList = [];
                for(let i = 0, len = this.selectList.length; i < len; i++) {
                    this.selectIdList.push(this.selectList[i].id);
                    this.selectManagerList.push(this.selectList[i].is_manager);
                }
            },

            // 刷新
            refresh() {
                // 获取所有账号
                this.$api.management_account.getUsers().then(response => {

                    // 将筛选条件置为初始状态
                    this.state = '';
                    this.input = '';
                    this.is_check = '';

                    // 获取数据
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

                    // 解决current-page 绑定的数据变了，但是页面当前页码并没有变的问题
                    this.current_page = 1;
                    this.pageshow = false;
                    this.$nextTick(() => {
                        this.pageshow = true
                    })
                })
            },

            search() {
                console.log(this.state);
                console.log(this.is_check);
                console.log(this.input);
            },

            // 清除筛选条件
            clear() {
                this.state = '';
                this.input = '';
                this.is_check = '';
            },

            // 冻结用户
            freeze(user) {
                // 最高管理者
                if (this.id === 1 && user.row.id !== 1) {
                    let formData = { id: user.row.id };
                    this.$api.management_account.freezeUser(formData).then(() => {
                        user.row.state = 0;
                    });
                }else {
                    if (user.row.id === 1) {
                        this.$swal.fire({
                            type: 'warning',
                            title: '无权限冻结超级管理员账号~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        }).then(); return false;
                    }else if(user.row.is_manager === 1) {
                        this.$swal.fire({
                            type: 'warning',
                            title: '无权限冻结管理员账号~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        }).then(); return false;
                    } else {
                        let formData = { id: user.row.id };
                        this.$api.management_account.freezeUser(formData).then(() => {
                            user.row.state = 0;
                        });
                    }
                }
            },

            // 解冻用户
            unfreeze(user) {
                let formData = { id: user.row.id };
                this.$api.management_account.unfreezeUser(formData).then(() => {
                    user.row.state = 1;
                });
            },

            // 删除账号
            unsets(user) {
                // 最高管理者
                if (this.id === 1 && user.row.id !== 1) {
                    this.user = user;
                    this.delVisible = true;
                }else {
                    if (user.row.id === 1) {
                        this.$swal.fire({
                            type: 'warning',
                            title: '无权限删除超级管理员账号~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        }).then(); return false;
                    }else if(user.row.is_manager === 1) {
                        this.$swal.fire({
                            type: 'warning',
                            title: '无权限删除管理员账号~',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        }).then(); return false;
                    } else {
                        this.user = user;
                        this.delVisible = true;
                    }
                }
            },

            deleteUser() {
                let formData = {
                    params: {
                        id: this.user.row.id,
                        current_page: this.current_page,
                    }
                };
                this.$api.management_account.unsetUser(formData).then(response => {
                    this.delVisible = false;
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

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

            // 批量删除账号
            batchUnset() {
                if (this.selectIdList.length === 0) {
                    this.$swal.fire({
                        type: 'warning',
                        title: '请勾选要删除的账号~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then(); return false;
                }

                for( let i = 0, len = this.selectIdList.length; i < len; i++) {
                    if (this.selectIdList[i] === 1) {
                        this.$swal.fire({
                            type: 'warning',
                            title: '请取消对超级管理员账号的勾选！',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        }).then(); return false;
                    }
                }

                for( let i = 0, len = this.selectManagerList.length; i < len; i++) {
                    if (this.selectManagerList[i] === 1 && this.id !== 1) {
                        this.$swal.fire({
                            type: 'warning',
                            title: '请取消管理员账号的勾选！',
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 2500,
                        }).then(); return false;
                    }
                }

                this.batchDelVisible = true;
            },

            batcheleteUser() {
                let formData = {
                    params: {
                        current_page: this.current_page,
                        selectIdList: this.selectIdList,
                    }
                }
                this.$api.management_account.batchUnsetUser(formData).then(response => {
                    this.batchDelVisible = false;
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;

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

            // 授权管理员
            authorization(user) {

                if (this.id !== 1) {
                    this.$swal.fire({
                        type: 'warning',
                        title: '您无此权限~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then(); return false;
                }

                let formData = { id: user.row.id };
                this.$api.management_account.authorization(formData).then(() => {
                    user.row.is_manager = 1;
                });

            },

            unAuthorization(user) {
                if (this.id !== 1 || user.row.id === 1) {
                    this.$swal.fire({
                        type: 'warning',
                        title: '您无此权限~',
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 2500,
                    }).then(); return false;
                }

                let formData = { id: user.row.id };
                this.$api.management_account.unAuthorization(formData).then(() => {
                    user.row.is_manager = 0;
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
                }
                this.$api.management_account.getCurrentPagesUsers(formData).then(response => {
                    this.tableData = response.data.data;
                    this.total_pages = response.data.meta.pagination.total_pages * 10;
                })
            },

            // 改变表格行的背景色
            tableRowClassName(rowData) {
                // 选中状态
                if (this.selectList.length > 0) {
                    for(let i = 0, len = this.selectList.length; i < len; i++) {
                        if (rowData.row.id === this.selectList[i].id) {
                            return 'success-row';
                        }
                    }
                }

                // 状态为冻结
                if (rowData.row.state === 0) {
                    return 'warning-row';
                }

                // 管理员状态
                if (rowData.row.is_manager === 1) {
                    return 'success-row';
                }else {
                    return '';
                }
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

    .search {
        cursor: pointer;
        color: #ffffff;
        padding: 5px 12px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        margin-right: 10px;
    }

    .search:hover {
        background: #1ad2b2;
    }

    .removeAll {
        cursor: pointer;
        color: #ffffff;
        padding: 5px 12px;
        background: #f56c6c;
        border: 1px solid #f56c6c !important;
        border-radius: 3px;
        font-size: 12px;
        margin-right: 10px;
    }

    .removeAll:hover {
        background-color: #ff6f6b;
    }

    .freeze {
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

    .freeze:hover {
        color: #ffffff;
        background: #f5af3f;
        border: 1px solid #f5af3f !important;
    }

    .unfreeze {
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

    .unfreeze:hover {
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

    .authorization {
        cursor: pointer;
        color: #ffffff;
        padding: 6px 12px;
        background: #1ac9a9;
        border: 1px solid #1ac9a9 !important;
        border-radius: 3px;
        font-size: 12px;
        line-height: 28px;
    }

    .authorization:hover {
        background: #1ad2b2;
    }

    /deep/ .el-checkbox__inner:hover {
        border-color: #70cd3c;
    }

    /deep/ .el-checkbox__input.is-checked+.el-checkbox__label {
        color: #e1f3d8;
    }

    /deep/ .el-checkbox__input.is-checked .el-checkbox__inner, /deep/ .el-checkbox__input.is-indeterminate .el-checkbox__inner {
        background-color: #70cd3c;
        border-color: #e1f3d8;
    }

    /deep/ .el-checkbox__input.is-focus .el-checkbox__inner {
        border-color: #70cd3c;
    }

    /deep/ .el-checkbox:last-child {
        margin-left: 9px;
        margin-bottom: 0;
    }

    /deep/ input:focus, /deep/ .el-select .el-input.is-focus .el-input__inner {
         border: 1px solid #1ac9a9 !important;
    }

    /deep/ .el-input__inner {
        height: 30px;
    }

    .handle-input {
        width: 150px;
        display: inline-block;
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

    .el-select-dropdown__item.selected {
        color: #1ac9a9;
        font-weight: normal;
    }

    /deep/ .el-table .warning-row {
        background: #fef6e7;
    }

    /deep/ .el-table .success-row {
        background: #f0f9eb;
    }

    .del-dialog-cnt{
        font-size: 16px;
        text-align: center
    }
</style>