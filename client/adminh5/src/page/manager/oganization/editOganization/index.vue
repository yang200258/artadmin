<template>
    <div class="oganization-container">
        <span>机构信息</span>
        <el-form :model="editForm" :rules="rules" ref="editManager">
            <el-form-item label="机构名称：" prop="organ_name"><el-row><el-input placeholder="请输入姓名" v-model="editForm.organ_name"></el-input></el-row></el-form-item>
            <el-form-item label="所属地区：" prop="organ_area"><el-row>
                    <el-select v-model="editForm.organ_area" placeholder="请选择所属地区">
                        <el-option v-for="item in districtOptions" :key="item.key" :label="item.label" :value="item.key"></el-option>
                    </el-select>
            </el-row></el-form-item>
            <el-form-item label="机构地址：" prop="organ_address"><el-row><el-input placeholder="请输入机构地址" v-model="editForm.organ_address"></el-input></el-row></el-form-item>
            <el-form-item label="联系人：" prop="name"><el-row><el-input placeholder="请输入密码" v-model="editForm.name"></el-input></el-row></el-form-item>
            <el-form-item label="联系电话1" prop="phone1"><el-row><el-input placeholder="请输入联系电话" v-model="editForm.phone[0]"></el-input></el-row></el-form-item>
            <el-form-item label="联系电话2" prop="phone2"><el-row><el-input placeholder="请输入联系电话" v-model="editForm.phone[1]"></el-input></el-row></el-form-item>
            <el-form-item label="联系电话3" prop="phone3"><el-row><el-input placeholder="请输入联系电话" v-model="editForm.phone[2]"></el-input></el-row></el-form-item>
            <el-form-item label="官网登录账号：" prop="username"><el-row><el-input placeholder="" v-model="editForm.username"></el-input></el-row></el-form-item>
            <el-form-item label="官网登录密码：" prop="password"><el-row><el-input type="password" placeholder="" v-model="editForm.password"></el-input></el-row></el-form-item>
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
            editForm: {organ_name: '',organ_area: '',organ_address: '',name: '',phone: [],username: '',password: ''},
            districtOptions: [{key: '0',label: '海口市'},{key: '1',label: '其他市县'}],
            rules:{organ_name: [{required: true,message: '请输入机构名称', trigger: 'blur'}],organ_area: [{required: true,message: '请输入所属地区', trigger: 'change'}],
                    organ_address: [{required: true,message: '请输入机构地址', trigger: 'blur'}],name: [{required: true,message: '请输入机构联系人', trigger: 'blur'}],
                    phone1: [{required: true,message: '请输入联系电话', trigger: 'blur'}],username: [{required: true,message: '请输入官网登录账号', trigger: 'blur'}],
                    password: [{required: true,message: '请输入官网登录密码', trigger: 'blur'}]}
        }
    },
    components: {

    },
    methods:{
        //保存机构
        submitForm: function(editForm){
            const {name,organ_address,organ_name,username,organ_area,password,phone} = editForm
            const id = this.$route.params.oganizationData.id
            this.$refs['editForm'].validate((valid)=> {
                if(valid) {
                    this.$axios({
                        url: '/organ/edit',
                        method: 'post',
                        data: {id,name,organ_address,organ_name,username,organ_area,password,phone}
                    }).then(res=> {
                        if(res && !res.error) {
                            this.$message.success(res.msg)
                        } else {
                            this.$message.warning(res.msg)
                        }
                        this.$router.push({
                            name: 'oganization'
                        })
                    }).catch(err=> {
                        console.log(err);
                    })
                } else {
                    this.$message.warning('请检查输入内容是否合法！')
                }
            })
        }
    },
    mounted(){
        if(this.$route.params) {
            this.editForm = this.$route.params.oganizationData
        } 
    }
}
</script>

<style lang="scss" scoped>
.oganization-container{
    padding-left: 20px;
    margin: 20px 50px;
    font-size: 16px;
    span{
        font-weight: 700;
        display: block;
        margin-bottom: 60px;
    }
    .el-input {
        display: block;
        width: 30%;
    }
    
    }
</style>


