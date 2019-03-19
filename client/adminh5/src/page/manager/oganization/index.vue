<template>
    <div class="oganization-container">
        <div class="oganization-header">
            <el-row>
                <el-col :span="4">
                    <p>机构名称：</p>
                    <el-input placeholder="机构名称" v-model="organ_name"></el-input>
                </el-col>
                <el-col :span="4" :offset="1">
                    <p>联系人：</p>
                    <el-input placeholder="联系人" v-model="name"></el-input>
                </el-col>
                <el-col :span="4" :offset="1">
                    <p>联系电话：</p>
                    <el-input placeholder="联系电话" v-model="phone"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="4">
                    <p>登录账号：</p>
                    <el-input placeholder="请输入登录账号" v-model="username"></el-input>
                </el-col>
                <el-col :span="4" :offset="1">
                    <el-button type="primary" @click="queryOganization">查询</el-button>
                </el-col>
            </el-row>
        </div>
        <div class="oganization-content">
            <table-data :isPagination="'true'" :currentPage="currentPage" :totalNumber="totalNumber" :pageSize="pageSize" :isEdit="'true'" :editName="'添加机构'" @editOption="addOganization"
            :head="head" :tableData="oganizationData" :loadingTable="loadingOganization" :isOption="'true'" :isEditTable="'true'" :isEditAccount="'true'" :isDeleteTable="'true'"
            :editTableName="'重置密码'" :editAccountName="'编辑'" :deleteTableName="'删除账号'" @editInfo="resetPassword" @editAccount="editAccount" @deleteInfo="deleteAccount" @handleCurrentChange="handleCurrentChange">
            </table-data>
            <el-dialog title="重置密码" :visible.sync="isResetPassword" width="30%" :before-close="handleClose" class="reset-password">
                <div class="reset-header">
                     <el-form :model="resetForm" :rules="rules" ref="resetForm">
                        <el-form-item label="输入密码" prop="password">
                            <el-row><el-input type="password" placeholder="请输入密码" v-model="resetForm.password"></el-input></el-row>
                        </el-form-item>
                        <el-form-item label="再次输入密码" prop="repeat_password">
                            <el-row><el-input type="password" placeholder="请再次输入密码" v-model="resetForm.repeat_password"></el-input></el-row>
                        </el-form-item>
                        <el-form-item>
                            <el-button @click="cancelReset">取 消</el-button>
                            <el-button type="primary" @click="submitForm(resetForm)">确定</el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </el-dialog>
        </div>
    </div>
</template>

<script>
import tableData from '@/components/common/tableData'
export default {
    data(){
        return{
            id: '',
            name: '',
            organ_name: '',
            username: '',
            phone: '',
            currentPage: 1,
            totalNumber: 0,
            pageSize: 50,
            head: [{key: 'organ_name',name: '机构名称'},{key: 'organ_address',name: '机构地址'},{key: 'name',name: '联系人'},{key: 'phone',name: '联系电话'},
                    {key: 'username',name: '官网登录账号'},{key: 'create_at',name: '添加时间'}],
            oganizationData: [],
            loadingOganization: false,
            isResetPassword: false,
            resetForm: {password: '',repeat_password: ''},
            rules: {password: [{required: true, message: '请输入密码', trigger: 'blur'},{min: 6,max: 16,message: '长度在 6 到 16 个字符'}],
                    repeat_password: [{required: true, message: '请再次输入密码', trigger: 'blur'},{min: 6,max: 16,message: '长度在 6 到 16 个字符'}]}
        }
    },
    mounted(){
        this.queryOganization()
    },
    components: {
        tableData
    },
    methods: {
        handleCurrentChange: function(val) {
            this.queryOganization(val)
        },
        //查询机构信息
        queryOganization: function(pn){
            this.loadingOganization = true
            const {name,organ_name,phone,username} = this
            pn = pn || '1'
            this.$axios({
                url: '/organ/list',
                method: 'post',
                data: {name,organ_name,phone,username,pn}
            }).then(res=>{
                console.log('查询到机构列表',res);
                if(res && !res.error) {
                    this.oganizationData = res.data.list
                    this.pageSize = res.data.page.limit
                    this.currentPage = res.data.page.pn
                    this.totalNumber = res.data.page.total
                } else {
                    this.$message.warning(res.msg)
                }
                this.loadingOganization = false
            }).catch(err=>{
                console.log(err)
                this.loadingOganization = false
            })
        },
        // 添加机构
        addOganization: function(){
            this.$router.push({
                name: 'addoganization'
            })
        },
        //重置密码
        resetPassword: function(scope){
            this.id = scope.row.id
            this.password = ''
            this.repeat_password = ''
            this.isResetPassword = true
            
        },
        //编辑账号
        editAccount:function(scope){
            scope.row.phone =  scope.row.phone.split(',')
            this.$router.push({
                name: 'editOganization',
                params: { oganizationData: scope.row}
            })
        },
        //删除账号
        deleteAccount:function(){

        },
        // 关闭重置密码框回调
        handleClose: function(){
            this.resetForm.password = ''
            this.resetForm.repeat_password = ''
            this.isResetPassword = false
        },
        //取消重置密码
        cancelReset: function(){
            this.resetForm.password = ''
            this.resetForm.repeat_password = ''
            this.isResetPassword = false
        },
        // 保存重置密码
        submitForm: function(resetForm){
            const {password,repeat_password} = resetForm
            const id = this.id
            this.$refs['resetForm'].validate((valid)=> {
                if(valid) {
                    this.$axios({
                        url: '/organ/reset',
                        method: 'post',
                        data: {password,repeat_password,id}
                    }).then(res=> {
                        if(res && !res.error) {
                            this.$message.success(res.msg)
                        } else {
                            this.$message.warning(res.msg)
                        }
                        this.resetForm.password = ''
                        this.resetForm.repeat_password = ''
                        this.isResetPassword = false
                    }).catch(err=> {
                        console.log(err);
                    })
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
    .oganization-container{
        font-size: 16px;
        margin: 20px 0;
        .oganization-header{
            padding-left: 20px;
            .el-row{
                margin-bottom: 25px;
                .el-col {
                    display: flex;
                    line-height: 40px;
                    p{
                        word-break: break-all;
                        white-space: nowrap;
                    }
                }
            }
            
        }
        .oganization-content{
            .reset-password{
                .reset-header{
                    .el-row{
                        margin: 20px 0;
                        .el-col{
                            display: flex;
                            p {
                                word-break: break-all;
                                white-space: nowrap;
                                line-height: 40px;
                            }
                        }
                    }
                    
                }
            }
        }
    }
</style>


