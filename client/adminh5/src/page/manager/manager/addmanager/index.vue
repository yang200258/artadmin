<template>
    <div class="addmanager-container">
        <el-form :model="addForm" :rules="rules" ref="addManager">
            <el-form-item label="姓名：" prop="name"><el-row><el-input placeholder="请输入姓名" v-model="addForm.name"></el-input></el-row></el-form-item>
            <el-form-item label="登录账号：" prop="user"><el-row><el-input placeholder="请输入登录账号" v-model="addForm.user"></el-input></el-row></el-form-item>
            <el-form-item label="登录密码：" prop="pass"><el-row><el-input type="password" placeholder="请输入密码" v-model="addForm.pass"></el-input></el-row></el-form-item>
            <el-form-item label="确认登录密码：" prop="repeat_password"><el-row><el-input type="password" placeholder="请再次输入密码" v-model="addForm.repeat_password"></el-input></el-row></el-form-item>
            <el-form-item label="用户身份：" prop="identity"><el-row><el-input placeholder="请输入用户身份" v-model="addForm.identity"></el-input></el-row></el-form-item>
            <el-form-item label="权限分配：" prop="right">
                <el-row>
                    <el-checkbox-group v-model="addForm.rightlist">
                        <el-checkbox v-for="item in rightlists" :key="item.name" :label="item.name" name="right">{{item.label}}</el-checkbox>
                    </el-checkbox-group>
                </el-row>
            </el-form-item>
            <el-form-item>
                <el-row><el-col :span="2" :push="10"><el-button type="primary" @click="submitForm(addForm)">立即添加</el-button></el-col></el-row>      
            </el-form-item>
        </el-form>
        
    </div>
</template>

<script>
export default {
    data(){
        return {
            addForm: {name: '',username: '',username: '',password: '',repeat_password: '',identity: '',identity: '',identity: '',rightlist: []},
            rules: {name: [{required: true, message: '请输入姓名', trigger: 'blur'}],user: [{required: true, message: '请输入登录账号', trigger: 'blur'}]
              ,pass: [{required: true, message: '请输入登录密码', trigger: 'blur'}],repeat_password: [{required: true, message: '请再次输入密码', trigger: 'blur'}],
              identity: [{required: true, message: '请输入用户身份', trigger: 'blur'}],
            },
            rightlists: [{name: 'apply',label: '报名管理'},{name: 'exam',label: '考试管理'},{name: 'msg',label: '消息管理'},{name: 'inform',label: '通知管理',},{name: 'admin',label: '管理员管理'}]
        }
    },
    methods: {
        submitForm: function(addForm) {
            console.log(addForm);
            const {name,identity,user,pass,repeat_password} = addForm
            let [apply,exam,msg,inform,admin] = ['0','0','0','0','0']
            for(let i=0,len =addForm.rightlist.length;i<len;i++ ) {
                if(addForm.rightlist[i] == 'apply') apply = 1 
                if(addForm.rightlist[i] == 'exam') exam = 1
                if(addForm.rightlist[i] == 'msg')   msg = 1
                if(addForm.rightlist[i] == 'inform')   inform = 1
                if(addForm.rightlist[i] == 'admin')   admin = 1
            }
            this.$refs['addManager'].validate((valid)=> {
                if(valid) {
                    this.$axios({
                        url: '/admin/add',
                        method: 'post',
                        data: {name,identity,username:user,password:pass,repeat_password,apply,exam,msg,inform,admin}
                    }).then(res=> {
                        if(res && !res.error) {
                            this.$message.success(res.msg)
                            this.$router.push({
                                name: 'manager'
                            })
                        } else {
                            this.$message.warn(res.msg)
                        }
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
    .addmanager-container{
        margin-top: 100px;
        margin-left: 50px;
        .el-input {
            display: block;
            width: 30%;   
        }
    }
</style>

