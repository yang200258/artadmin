<template>
    <div class="editteacher-container">
        <div class="editteacher-header">
            <p>老师信息：</p>
        </div>
        <el-form :model="editForm" :rules="rules" ref="editTeacher">
            <el-form-item label="姓名：" prop="name"><el-row><el-input placeholder="请输入老师姓名" v-model="editForm.name"></el-input></el-row></el-form-item>
            <el-form-item label="性别：" prop="sex"><el-row>
                    <el-radio-group v-model="editForm.sex">
                        <el-radio label="男"></el-radio>
                        <el-radio label="女"></el-radio>
                    </el-radio-group>
            </el-row></el-form-item>
            <el-form-item label="联系电话" prop="phone"><el-row><el-input placeholder="请输入联系电话" v-model="editForm.phone"></el-input></el-row></el-form-item>
            <el-form-item label="所属机构：" prop="organ_uid"><el-row>
                <el-select v-model="editForm.organ_uid" placeholder="请选择所属机构">
                    <el-option v-for="item in organ" :key="item.id" :label="item.organ_name" :value="item.id"></el-option>
                </el-select>
            </el-row></el-form-item>
            <el-form-item label="官网登录账号：" prop="username"><el-row><el-input placeholder="" v-model="editForm.username"></el-input></el-row></el-form-item>
            <el-form-item>
                <el-row><el-col :span="2" :push="10"><el-button type="primary" @click="submitForm(editForm)">保存修改</el-button></el-col></el-row>      
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
export default {
    data(){
        return{
            id: '',
            editForm: {
                name: '',
                phone: '',
                username: '',
                sex: '',
                organ_uid: ''
            },
            rules:{name: [{required: true,message: '请输入老师姓名', trigger: 'blur'}],phone: [{required: true,message: '请输入联系电话', trigger: 'blur'}],
                    username: [{required: true,message: '请输入官网登录账号：', trigger: 'blur'}],sex: [{required: true,message: '请选择性别', trigger: 'change'}],organ_uid: [{required: true,message: '请选择所属机构', trigger: 'change'}]}
        }
    },
    components: {

    },
    methods: {
        // 保存老师信息
        submitForm: function(editForm){
            const {name,phone,username,sex,organ_uid} = editForm
            const phoneArr = phone.split(';')
            const id= this.$route.params.teacherData.id
            this.$refs['editTeacher'].validate((valid)=> {
                if(valid) {
                    this.$axios({
                        url: '/teacher/edit',
                        method: 'post',
                        data: {name,phone:phoneArr,username,sex,organ_uid,id}
                    }).then(res=> {
                        if(res && !res.error) {
                            this.$message.success(res.msg)
                        } else {
                            this.$message.warn(res.msg)
                        }
                        this.$router.push({
                            name: 'signTeacher'
                        })
                    }).catch(err=> {
                        console.log(err);
                    })
                }
            })
        }
    },
    mounted(){
        if(this.$route.params) {
            this.$set(this,'editForm',this.$route.params.teacherData)
            // this.id = this.$route.params.teacherData.id
        }
    },
    computed: {
        organ(){
            return this.$store.state.auth.organ
        }
    }
}
</script>

<style lang="scss" scoped>
.editteacher-container{
    margin-left: 100px;
    margin-top: 100px;
    .editteacher-header{
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 40px;
    }
    .el-input {
        display: block;
        width: 29%;
    }
}
</style>


