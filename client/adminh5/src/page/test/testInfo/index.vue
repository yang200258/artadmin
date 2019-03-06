<template>
    <div class="container">
        <div class="header">
            <el-row>
                <el-col :span="6"><p>考试编号：</p><el-input v-model="number" placeholder="考试编号"></el-input></el-col>
                <el-col :span="6"><p>考试名称：</p><el-input v-model="name" placeholder="考试名称"></el-input></el-col>
                <el-col :span="6"><p>当前状态：</p>
                    <template>
                        <el-select v-model="status" placeholder="全部">
                            <el-option v-for="item in statusOptions" :key="item.value" :label="item.label" :value="item.value"> </el-option>
                        </el-select>
                    </template>
                </el-col>
            </el-row>
            <el-row>
                <el-col :span="6"><p>报名时间：</p>
                    <el-date-picker v-model="apply_time" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                </el-col>
                <el-col :span="6"><p>考试时间：</p>
                    <el-date-picker v-model="exam_time" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                </el-col>
                <el-col :span="6">
                    <el-button type="primary" style="width:30%" @click.prevent="queryTestInfo">查询</el-button>
                </el-col>
            </el-row>
        </div>
        <table-data :isPagination="'true'" :totalNumber="totalNumber" :currentPage="currentPage" :pageSize="pageSize" :head="head" :tableData="testData" :isOption="'true'"
        :isEditTable="'true'" :isEdit="'true'" :editTableName="'查看详情'" :loadingTable="isLoading" @editInfo="editTest" @editOption="addTest" :editName="'添加考试'"></table-data>
    </div>
</template>

<script>
import util from '@/util/util'
export default {
    data() {
        return {
            number: '',
            name: '',
            status: '',
            statusOptions: [{value: '',label: '全部'},{value: '1',label: '未报名'},{value: '2',label: '报名中'},{value: '3',label: '未考试'},{value: '4',label: '考试中'},{value: '5',label: '已结束'}],
            apply_time: '',
            exam_time: '',
            currentPage: 1,
            pageSize: 20,
            totalNumber: 20,
            head: [{key: 'number',name: '考试编号'},{key: 'name',name: '考试名称'},{key: 'site_num',name: '考试地点数'},{key: 'room_num',name: '考场数量'},
                    {key: 'applyTime',name: '报名起止时间'},{key: 'examTime',name: '考试起止时间'},{key: 'status_name',name: '当前状态'}],
            testData: [],
            isLoading: false
        }
    },
    mounted(){
        this.queryTestInfo()
    },
    methods: {
       handleSizeChange(val) {
        console.log(`每页 ${val} 条`);
      },
      handleCurrentChange(val) {
        // console.log(`当前页: ${val}`);
        this.queryTestInfo(val)
      },
      queryTestInfo(pn){
          const {name,number,status,apply_time,exam_time } = this
          pn = pn || 1
          const apply_time_start = util.filterDate(apply_time[0])
          const apply_time_end  = util.filterDate(apply_time[1])
          const exam_time_start  = util.filterDate(exam_time[0])
          const exam_time_end  = util.filterDate(exam_time[1])
          this.isLoading = true
          this.$axios({
              url : '/exam/list',
              method: 'post',
              data: {name,number,status,apply_time_start,apply_time_end,exam_time_start,exam_time_end,pn}
          }).then(res=> {
              console.log('查询考试列表信息',res);
              if(res && !res.error) {
                  res.data.list.forEach(item=> {
                      item.applyTime = item.apply_time_start + '---' + item.apply_time_end
                      item.examTime = item.exam_time_start + '---' + item.exam_time_end
                  })
                this.testData = res.data.list
              } else {
                  this.$message.warning(res.msg)
              }
              this.isLoading = false
          }).catch(err=> {
              console.log(err);
              this.isLoading = false
          })
      },
      addTest(){
          this.$router.push({
              name: 'addTest'
          })
      },
      editTest(){
          this.$router.push({name: 'editTest'})
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

