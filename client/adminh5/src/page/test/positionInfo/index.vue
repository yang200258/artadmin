<template>
    <div class="container">
        <div class="header">
            <!-- <el-row>
                <el-col :span="6"><p>考试编号：</p><el-input v-model="number" placeholder="考试编号"></el-input></el-col>
                <el-col :span="6"><p>考试地点：</p><el-input v-model="name" placeholder="考试地点"></el-input></el-col>
            </el-row> -->
            <el-row>
                <el-col :span="9"><p>考试日期：</p>
                    <el-date-picker v-model="testTime" type="daterange" start-placeholder="考试开始日期" end-placeholder="考试结束日期" :default-time="['00:00:00', '23:59:59']"></el-date-picker>
                </el-col>
                <el-col :span="5">
                    <el-button type="primary" style="width:30%" @click.prevent="queryTestInfo">查询</el-button>
                </el-col>
            </el-row>
        </div>
        <table-data :isPagination="'true'" :totalNumber="totalNumber" :currentPage="currentPage" :pageSize="pageSize" :isOption="'true'" :isEditTable="'true'" :editTableName="'考生安排'"
        :loadingTable="isLoading" :head="head" :tableData="testLocationData" @handleCurrentChange="handleCurrentChange" @editInfo="editExaminee"></table-data>
    </div>
</template>


<script>
import util from '@/util/util'
import tableData from '@/components/common/tableData'
export default {
    data(){
        return{
            testTime: '',
            number: '',
            name: '',
            pageSize: 50,
            totalNumber: 0,
            currentPage: 1,
            head: [{key: 'address',name: '考试地点'},{key: 'room',name: '考场'},{key: 'exam_date',name: '考试日期'},
                    {key: 'exam_time',name: '考试时间'},{key: 'examinee_num',name: '考生人数'}],
            testLocationData: [],
            isLoading: false
        }
    },
    components: {
        tableData
    },
    mounted(){
        // this.$set(this,'',this.$route.params)
        this.number = this.$route.params.number
        this.name = this.$route.params.name
        this.queryTestInfo()
    },
    methods: {
        // 分页
        handleCurrentChange(val) {
            this.queryTestInfo(val)
        },
        //查询考场信息
        queryTestInfo(pn){
            this.isLoading = true
            const {name,number,testTime} = this
            const exam_time_start = util.filterDate(testTime[0])
            const exam_time_end = util.filterDate(testTime[1])
            pn = pn || 1
            this.$axios({
                url : '/exam/room',
                method: 'post',
                data: {name,number,exam_time_start,exam_time_end,pn}
            }).then(res=> {
                console.log('查询到的考场数据',res);
                if(res && !res.error) {
                    res.data.list.forEach(item=> {
                        item.exam_date = item.exam_time.split(' ')[0]
                        item.exam_time = item.exam_time.split(' ')[1]
                    })
                    this.testLocationData = res.data.list
                    this.pageSize = res.data.page.limit
                    this.totalNumber = res.data.page.total
                    this.currentPage = res.data.page.pn
                } else {
                    this.$message.warning(res.msg)
                }
                 this.isLoading = false
            }).catch(err=> {
                console.log(err);
                this.isLoading = false
            })
        },
        //编辑考生安排信息
        editExaminee(scope) {
            this.$router.push({
                name: 'editLocation',
                params: {
                    exam_site_id: scope.row.id,
                    exam_id: scope.row.exam_id,
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
    .container{
        padding-left: 20px;
        margin-top: 20px;
        font-size: 16px;
        .header{
            .el-row{
                margin-top: 40px;
                &:first-child{
                    margin-top: 20px;
                }
                .el-col{
                    display: flex;
                    &:first-child{
                        margin-left: 0;
                    }
                    margin-left: 50px;
                    p{
                        word-break:keep-all;
                        white-space:nowrap;
                        text-align: center;
                        vertical-align: center;
                        line-height: 40px;
                    }
                }
            }
        }
        .el-main{
            .queryResult {
                display: flex;
                flex-direction: column;
                justify-content: center;
                margin-top: 40px;
                .el-header {
                    display: flex;
                    align-items: flex-end;
                    padding-left: 0;
                    justify-content: space-between;
                }
                .el-main {
                    padding-left: 0;
                }
            }
        }
    }
</style>


