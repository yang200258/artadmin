<template>
    <div class="oganization-container">
        <span>机构信息</span>
        <el-form :model="addForm" :rules="rules" ref="addOgan">
            <el-form-item label="机构名称：" prop="organ_name"><el-row><el-input placeholder="请输入机构名称" v-model="addForm.organ_name"></el-input></el-row></el-form-item>
            <el-form-item label="所属地区：" prop="organ_area"><el-row>
                    <el-select v-model="addForm.organ_area" placeholder="请选择所属地区">
                        <el-option v-for="item in districtOptions" :key="item.key" :label="item.label" :value="item.key"></el-option>
                    </el-select>
            </el-row></el-form-item>
            <el-form-item label="机构地址：" prop="organ_address"><el-row><el-input placeholder="请输入机构地址" v-model="addForm.organ_address"></el-input></el-row></el-form-item>
            <el-form-item label="联系人：" prop="name"><el-row><el-input placeholder="请输入联系人" v-model="addForm.name"></el-input></el-row></el-form-item>
            <el-form-item label="联系电话1" prop="phone"><el-row><el-input placeholder="请输入联系电话" v-model="addForm.phone[0]"></el-input></el-row></el-form-item>
            <el-form-item label="联系电话2"><el-row><el-input placeholder="请输入联系电话" v-model="addForm.phone[1]"></el-input></el-row></el-form-item>
            <el-form-item label="联系电话3"><el-row><el-input placeholder="请输入联系电话" v-model="addForm.phone[2]"></el-input></el-row></el-form-item>
            <el-form-item label="官网登录账号：" prop="user"><el-row><el-input placeholder="" v-model="addForm.user"></el-input></el-row></el-form-item>
            <el-form-item label="官网登录密码：" prop="pass"><el-row><el-input type="pass" placeholder="" v-model="addForm.pass"></el-input></el-row></el-form-item>
            <el-form-item>
                <el-row><el-col :span="2" :push="10"><el-button type="primary" @click="submitForm(addForm)">立即添加</el-button></el-col></el-row>      
            </el-form-item>
        </el-form>
    </div>
        
</template>

<script>
import {mapActions} from 'vuex'
export default {
    data(){
        return{
            addForm: {organ_name: '',organ_area: '',organ_address: '',name: '',phone: [],user: '',pass: ''},
            districtOptions: [{key: '0',label: '海口市'},{key: '1',label: '其他市县'}],
            rules:{organ_name: [{required: true,message: '请输入机构名称', trigger: 'blur'}],organ_area: [{required: true,message: '请输入所属地区', trigger: 'change'}],
                    organ_address: [{required: true,message: '请输入机构地址', trigger: 'blur'}],name: [{required: true,message: '请输入机构联系人', trigger: 'blur'}],
                    phone: [{required: true,message: '请输入联系电话', trigger: 'blur'}],user: [{required: true,message: '请输入官网登录账号', trigger: 'blur'}],
                    pass: [{required: true,message: '请输入官网登录密码', trigger: 'blur'}]}
        }
    },
    components: {

    },
    methods:{
        ...mapActions({
            getOrgan: 'auth/getOrgan',
        }),
        //保存机构
        submitForm: function(addForm){
            const {name,organ_address,organ_name,user,organ_area,pass,phone} = addForm
            this.$refs['addOgan'].validate((valid)=> {
                if(valid) {
                    this.$axios({
                        url: '/organ/add',
                        method: 'post',
                        data: {name,organ_address,organ_name,username:user,organ_area,password:pass,phone}
                    }).then(res=> {
                        if(res && !res.error) {
                            this.getOrgan().then(r=> {
                                if(r && !r.error) {
                                    this.$store.commit('auth/setOrgan',JSON.parse(window.localStorage.getItem('organ')))
                                    this.$message.success('添加机构成功！')
                                }
                            })
                        } else {
                            this.$message.warning(res.msg)
                        }
                        this.$router.push({
                            name: 'oganization'
                        })
                    }).catch(err=> {
                        console.log(err);
                    })
                }
            })
        }
    },
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


