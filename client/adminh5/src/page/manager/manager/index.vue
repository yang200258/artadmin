<template>
    <div class="manager-container">
        <div class="manager-header">
            <el-row type="flex" justify-content="center">
                <el-col :span="2">
                    <p>姓名：</p>
                    <el-input placeholder="请输入姓名" v-model="name"></el-input>
                </el-col>
                <el-col :span="6" :offset="2">
                    <p>登录账号：</p>
                    <el-input placeholder="请输入登录账号" v-model="username"></el-input>
                </el-col>
                <el-col :span="4" :offset="4">
                    <el-button type="primary" @click="queryManager">查询</el-button>
                </el-col>
            </el-row>
        </div>
        <div class="manager-content">
            <el-row>
                <p>共有{{total}}位管理员</p>
            </el-row>
            <table-data :isPagination="'true'" :head="managerHead" :tableData="managerData" :isEdit="'true'" :editName="'新增管理员'" @editOption="addManager" :isOption="'true'" :isEditTable="'true'" :editTableName="'重置密码'" :isEditAccount="'true'" 
            :editAccountName="'编辑账号'" :isDeleteTable="'true'" :deleteTableName="'删除账号'" @editInfo="editInfo" @editAccount="editAccount" @deleteInfo="deleteInfo" 
            :loadingTable="loadingManager" :totalNumber="total" :pageSaze="limit" :currentPage="pn" @handleCurrentChange="handleCurrentChange">
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
            username: '',
            managerNumber: 0,
            managerHead: [{key: 'id',name: '管理员ID'},{key: 'name',name: '姓名'},{key: 'username',name: '登录账号'},
                          {key: 'identity',name: '管理员身份'},{key: 'create_at',name: '添加时间'}],
            managerData: [],
            loadingManager: false,
            isResetPassword: false,
            total: 0,
            limit: 50,
            pn: 1,
            rightlist: [],
            resetForm: {password: '',repeat_password: ''},
            rules: {password: [{required: true, message: '请输入密码', trigger: 'blur'},{min: 6,max: 16,message: '长度在 6 到 16 个字符'}],
                    repeat_password: [{required: true, message: '请再次输入密码', trigger: 'blur'},{min: 6,max: 16,message: '长度在 6 到 16 个字符'}]}
        }
    },
    mounted(){
        this.queryManager()
    },
    components: {
        tableData
    },
    methods: {
        handleCurrentChange: function(val){
            this.queryManager(val)
        },
        //查询管理员
        queryManager: function(pn){
            const {name,username} = this
            pn = pn || 1
            this.loadingManager = true
            this.$axios({
              url : '/admin/list',
              method: 'post',
              data: {name,username,pn}
          }).then(res=> {
              console.log('查询到管理员列表',res);
              if(res && !res.error) {
                  this.managerData = res.data.list
                  this.total = res.data.page.total
                  this.pn = res.data.page.pn
                  this.limit = res.data.list.limit
              } else {
                  this.$message.warning(res.msg)
              }
              this.loadingManager = false
          }).catch(err=> {
              console.log(err);
              this.loadingManager = false
          })
        },
        //添加管理员
        addManager(){
            this.$router.push({
                name: 'addmanager'
            })
        },
        //重置密码
        editInfo: function(scope){
            this.id = scope.row.id
            this.isResetPassword = true
            
        },
        //编辑账号
        editAccount: function(scope){
            this.$store.commit('manager/setManagerdata',scope.row)
            this.$router.push({
                name: 'editmanager',
            })
        },
        //删除账号
        deleteInfo: function(scope){
            const id = scope.row.id
            this.$axios({
                url: '/admin/delete',
                method: 'post',
                data: {id}
            }).then(res => {
                if(res  && !res.error) {
                    this.$message.success('删除成功！')
                } else {
                    this.$message.warning(res.msg)
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        // 关闭重置密码框回调
        handleClose: function(){
            this.isResetPassword = false
            this.password = ''
            this.repeat_password = ''
        },
        //取消重置密码
        cancelReset: function(){
            this.isResetPassword = false
            this.password = ''
            this.repeat_password = ''
        },
        // 保存重置密码
        submitForm: function(resetForm){
            this.$refs.resetForm.validate(valid=> {
                if(valid) {
                    const {password,repeat_password} = resetForm
                    const id = this.id
                    this.$axios({
                        url: '/admin/reset',
                        method: 'post',
                        data: {id,password,repeat_password}
                    }).then(res=> {
                        if(res && !res.error) {
                            this.$message.success(res.msg)
                        } else {
                            this.$message.warning(res.msg)
                        }
                        this.isResetPassword = false
                    }).catch(err=> {
                        console.log(err);
                        this.isResetPassword = false
                    })
                }
            })
            
        }
    }
}
</script>


<style lang="scss" scoped>
    .manager-container{
        padding-left: 20px;
        font-size: 16px;
        margin-top: 40px;
        .manager-header{
            .el-col{
                display: flex;
                line-height: 40px;
                p {
                    word-break: break-all;
                    white-space:nowrap;
                }
            }
        }
        .manager-content {
            margin-top: 40px;
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


