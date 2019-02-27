<template>
    <div class="signteacher-container">
        <div class="signteacher-header">
            <el-row>
                <el-col :span="4">
                    <p>老师姓名：</p>
                    <el-input placeholder="老师姓名" v-model="name"></el-input>
                </el-col>
                <el-col :span="4" :offset="1">
                    <p>联系电话：</p>
                    <el-input placeholder="联系电话" v-model="phone"></el-input>
                </el-col>
                <el-col :span="4" :offset="1">
                    <p>所属机构：</p>
                    <el-input placeholder="所属机构" v-model="organ_name"></el-input>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="4">
                    <p>登录账号：</p>
                    <el-input placeholder="请输入登录账号" v-model="username"></el-input>
                </el-col>
                <el-col :span="4" :offset="1">
                    <el-button type="primary" @click="querySignTeacher">查询</el-button>
                </el-col>
            </el-row>
        </div>
        <div class="signteacher-content">
            <table-data :isPagination="'true'" :currentPage="currentPage" :totalNumber="totalNumber" :pageSize="pageSize" :isEdit="'true'" :editName="'添加老师'" @editOption="addTeacher"
            :head="head" :tableData="signTeacherData" :loadingTable="loadingSignTeacher" :isOption="'true'" :isEditTable="'true'" :isEditAccount="'true'" :isDeleteTable="'true'"
            :editTableName="'重置密码'" :editAccountName="'编辑账号'" :deleteTableName="'删除账号'" @editInfo="resetPassword" @editAccount="editAccount" @deleteInfo="deleteAccount"
            @handleCurrentChange="handleCurrentChange">
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
export default {
    data(){
        return{
            name: '',
            phone: '',
            organ_name: '',
            username: '',
            currentPage: 1,
            totalNumber: 0,
            pageSize: 50,
            head: [{key: 'name',name: '姓名'},{key: 'sex',name: '性别'},{key: 'phone',name: '联系电话'},{key: 'organ_name',name: '所属机构'},
                    {key: 'username',name: '官网登录账号'},{key: 'create_at',name: '添加时间'}],
            signTeacherData: [],
            loadingSignTeacher: false,
            isResetPassword: false,
            resetForm: {password: '',repeat_password: ''},
            rules: {password: [{required: true, message: '请输入密码', trigger: 'blur'}],repeat_password: [{required: true, message: '请再次输入密码', trigger: 'blur'}]}
        }
    },
    methods: {
        handleCurrentChange(val){
            this.querySignTeacher(val)
        },
        //查询报名老师
        querySignTeacher: function(pn){
            const {name,phone,organ_name,username} = this
            pn = pn || 1
            this.loadingSignTeacher = true
            this.$axios({
                url: '/manager/teacher/list',
                method: 'post',
                data: {name,phone,organ_name,username,pn}
            }).then(res=>{
                if(res && !res.error) {
                    this.signTeacherData = res.data.list
                    res.data.list.forEach(item=> {
                        if(item.organ) {
                            item.organ_name = item.organ.organ_name
                        }
                    })
                    this.pageSize = res.data.page.limit
                    this.totalNumber = res.data.page.total
                    this.currentPage = res.data.page.pn
                } else {
                    alert(res.msg)
                }
                this.loadingSignTeacher = false
            }).catch(err=>{
                console.log(err)
                this.loadingSignTeacher = false
            })
        },
        // 添加老师
        addTeacher: function(){
            this.$router.push({
                name: 'addTeacher'
            })
        },
        //重置密码
        resetPassword: function(scope){
            this.id = scope.row.id
            this.isResetPassword = true
        },
        //编辑账号
        editAccount:function(scope){
            this.$router.push({
                name: 'editTeacher',
                params: {
                    teacherData: scope.row
                }
            })
        },
        //删除账号
        deleteAccount:function(){

        },
        // 关闭重置密码框回调
        handleClose: function(){
            this.password = ''
            this.passwordagain = ''
            this.isResetPassword = false
        },
        //取消重置密码
        cancelReset: function(){
            this.password = ''
            this.passwordagain = ''
            this.isResetPassword = false
        },
        // 保存重置密码
        submitForm: function(resetForm){
            const {password,repeat_password} = resetForm
            const id = this.id
            this.$refs['resetForm'].validate((valid)=> {
                if(valid) {
                    this.$axios({
                        url: '/manager/teacher/reset',
                        method: 'post',
                        data: {password,repeat_password,id}
                    }).then(res=> {
                        if(res && !res.error) {
                            alert(res.msg)
                        } else {
                            alert(res.msg)
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
    .signteacher-container{
        padding-left: 20px;
        font-size: 16px;
        margin: 20px 0;
        .signteacher-header{
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
        .signteacher-content{
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


