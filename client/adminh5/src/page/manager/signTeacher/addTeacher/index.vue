<template>
    <div class="addteacher-container">
        <div class="teacher-header">
            <p>老师信息：</p>
        </div>
        <el-form :model="addForm" :rules="rules" ref="addTeacher">
            <el-form-item label="姓名：" prop="name"><el-row><el-input placeholder="请输入老师姓名" v-model="addForm.name"></el-input></el-row></el-form-item>
            <el-form-item label="性别：" prop="sex"><el-row>
                <el-radio-group v-model="addForm.sex">
                    <el-radio label="男"></el-radio>
                    <el-radio label="女"></el-radio>
                </el-radio-group>
            </el-row></el-form-item>
            <el-form-item label="联系电话" prop="phone"><el-row><el-input placeholder="请输入联系电话" v-model="addForm.phone"></el-input></el-row></el-form-item>
            <el-form-item label="所属机构：" prop="organ_uid"><el-row>
                <el-select v-model="addForm.organ_uid" placeholder="请选择所属机构">
                    <el-option v-for="item in organ" :key="item.id" :label="item.organ_name" :value="item.id"></el-option>
                </el-select>
            </el-row></el-form-item>
            <el-form-item label="官网登录账号：" prop="user"><el-row><el-input placeholder="" v-model="addForm.user"></el-input></el-row></el-form-item>
            <el-form-item label="官网登录密码：" prop="pass"><el-row><el-input type="password" placeholder="" v-model="addForm.pass"></el-input></el-row></el-form-item>
            <el-form-item>
                <el-row><el-col :span="2" :push="10"><el-button type="primary" @click="submitForm(addForm)">立即添加</el-button></el-col></el-row>      
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addForm: {
                name: '',
                phone: '',
                user: '',
                pass: '',
                sex: '',
                organ_uid: ''
            },
            rules:{name: [{required: true,message: '请输入老师姓名', trigger: 'blur'}],phone: [{required: true,message: '请输入联系电话', trigger: 'blur'}],
                    user: [{required: true,message: '请输入官网登录账号：', trigger: 'blur'}],pass: [{required: true,message: '请输入官网登录密码：', trigger: 'blur'}],
                    sex: [{required: true,message: '请选择性别', trigger: 'change'}],organ_uid: [{required: false,message: '请选择所属机构', trigger: 'change'}]}
        }
    },
    computed: {
        organ(){
            return this.$store.state.auth.organ
        }
    },
    methods: {
        // 添加老师信息
        submitForm(addForm) {
            const {name,phone,user,pass,sex,organ_uid} = addForm
            const phoneArr = phone.split(';')
            this.$refs['addTeacher'].validate((valid)=> {
                if(valid) {
                    this.$axios({
                        url: '/teacher/add',
                        method: 'post',
                        data: {name,phone:phoneArr,username:user,password:pass,sex,organ_uid}
                    }).then(res=> {
                        if(res && !res.error) {
                            this.$message.success(res.msg)
                            this.$router.push({
                            name: 'signTeacher'
                        })
                        } else {
                            this.$message.warn(res.msg)
                        }
                        
                    })
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
.addteacher-container{
    margin-left: 100px;
    .teacher-header{
        font-weight: 700;
        font-size: 20px;
        margin: 50px 0;
    }
    .el-input {
        width: 29%;
        display: block;
    }
}
    
</style>


