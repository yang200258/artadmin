<template>
    <el-container class="signinfo">
            <el-header>
                <el-row>
                    考试信息
                </el-row>
            </el-header>
            <el-main>
                <div class="baseinfo">
                    <el-form :model="baseinfo" :rules="rules">
                        <el-form-item label="考试编号：" prop="number">
                            <el-input v-model="baseinfo.number"></el-input>
                        </el-form-item>
                        <el-form-item label="考试名称：" prop="number">
                            <el-input v-model="baseinfo.name"></el-input>
                        </el-form-item>
                        <el-form-item label="报名起止日期：" prop="applyTime">
                            <el-date-picker v-model="baseinfo.applyTime" type="datetimerange" start-placeholder="报名开始日期" end-placeholder="报名结束日期" :default-time="['00:00:00', '23:59:59']"></el-date-picker>
                        </el-form-item>
                        <el-form-item label="考试起止日期：" prop="examTime">
                            <el-date-picker v-model="baseinfo.examTime" type="datetimerange" start-placeholder="报名开始日期" end-placeholder="报名结束日期" :default-time="['00:00:00', '23:59:59']"></el-date-picker>
                        </el-form-item>
                    </el-form>
                </div>
            </el-main>
        </el-container>
</template>
<script>
export default {
    data(){
        return {
            rules: {number: [{required: true,message: '请输入考试编号',trigger: 'blur'}],name: [{required: true,message: '请输入考试名称',trigger: 'blur'}],
            applyTime: [{required: true,message: '请选择报名起止日期：',trigger: 'change'}],examTime: [{required: true,message: '请选择考试起止日期：',trigger: 'change'}]}
        }
    },
    computed: {
        baseinfo(){
            return this.$store.state.test.baseinfo
        }
    }
}
</script>


<style lang="scss" scoped>
    .container {
        font-size: 18px;
        .signinfo {
            margin-top: 40px;
            .el-header {
                background-color: #545c64;
                height: 50px!important; 
                .el-row {
                    font-size: 18px;
                    color: #fff;
                    line-height: 50px;
                }
            }
            .el-input,.el-date-picker {
                display: block;
                width: 30%;
            }
        }
    }
</style>

