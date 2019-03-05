<template>
    <div class="container">
        <div class="queryInfo">
            <el-row :gutter="40">
                <el-col :span="4"><p>考生姓名：</p><el-input v-model="name" class="" placeholder="考生姓名" clearable></el-input></el-col>
                <el-col :span="4"><p>报考专业：</p>
                    <el-select v-model="domain" placeholder="全部" @change="changeSelect">
                        <el-option v-for="item in domainOptions" :key="item.key" :label="item.key" :value="item.label"> </el-option>
                    </el-select>
                </el-col>
                <el-col :span="4" :offset="0"><p>报考级别：</p>
                    <el-select v-model="level" placeholder="全部">
                        <el-option v-for="item in levelOptions" :key="item.key" :label="item.key" :value="item.value"> </el-option>
                    </el-select>
                </el-col>
                <el-col :span="8">
                    <p>报名时间：</p>
                    <el-date-picker v-model="signTime" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                </el-col>
            </el-row>
            <el-row :gutter="40">
                <el-col :span="4"><p>证件类型：</p>
                    <el-select v-model="id_type" placeholder="全部">
                        <el-option v-for="item in idCardOptions" :key="item.value" :label="item.label" :value="item.value"> </el-option>
                    </el-select>
                </el-col>
                <el-col :span="6"><p>证件号码：</p>
                    <el-input v-model="id_number" class="" placeholder="证件号码" clearable></el-input>
                </el-col>
                <el-col :span="5"><p>负责报名机构：</p>
                    <el-input v-model="organ_name" class="" placeholder="报名机构" clearable></el-input>
                </el-col>
                <el-col :span="5">
                    <p>负责报名老师：</p>
                    <el-input v-model="teacher_name" class="" placeholder="老师姓名" clearable></el-input>
                </el-col>
            </el-row>
            <el-row :gutter="40">
                <el-col :span="4"><p>审核状态：</p>
                    <el-select v-model="status" placeholder="全部">
                        <el-option v-for="item in verifyOptions" :key="item.value" :label="item.label" :value="item.value"> </el-option>
                    </el-select>
                </el-col>
                <el-col :span="4"><p>当前进度：</p>
                    <el-select v-model="plan" placeholder="全部">
                        <el-option v-for="item in progressOptions" :key="item.value" :label="item.label" :value="item.value"> </el-option>
                    </el-select>
                </el-col>
                <el-col :span="5"><p>是否存在缺考顺延：</p>
                    <el-select v-model="postpone" placeholder="全部">
                        <el-option v-for="item in islostTestOptions" :key="item.value" :label="item.label" :value="item.value"> </el-option>
                    </el-select>
                </el-col>
                <el-col :span="5">
                    <el-button type="primary" @click.prevent="queryInfo">查询</el-button>
                </el-col>
            </el-row>
        </div>
        <div class="download">
            <el-button type="primary">导出考级名单报名表</el-button>
            <el-button type="primary">导出考级录入系统报名表</el-button>
        </div>
        <el-container class="queryResult">
            <table-data :isPagination="'true'" :totalNumber="totalNumber" :currentPage="currentPage" :pageSize="pageSize" :head="head" :tableData="signList" :isOption="'true'"
            :isEditTable="'true'" :editTableName="'查看详情'" isSelected="'true'" :loadingTable="isLoading" @editInfo="signDetail" @handleCurrentChange="handleCurrentChange">
            </table-data>
        </el-container>
    </div>
</template>

<script>
import {mapState} from 'vuex'
import util from '@/util/util'
export default {
    data() {
        return {
            name: '',
            domain: [],
            level: '',
            levelOptions: [],
            signTime: [],
            id_type: '',
            idCardOptions: [{value: '',label: '全部'},{value: '身份证',label: '身份证'},{value: '护照',label: '护照'}],
            id_number: '',
            organ_name : '',
            teacher_name : '',
            status : '',
            verifyOptions : [{value: '',label: '全部'},{value: '1',label: '待审核'},{value: '2',label: '不通过'},{value: '4',label: '已通过'},{value: '3',label: '无需审核'}],
            plan : '',
            progressOptions: [{value: '',label: '全部'},{value: '1',label: '审核中'},{value: '3',label: '已失效'},{value: '2',label: '待缴费'},{value: '4',label: '已缴费'}],
            postpone : '',
            islostTestOptions: [{value: '',label: '全部'},{value: '1',label: '是'},{value: '0',label: '否'}],
            head: [{key: 'name',name: '考生姓名'},{ key: 'domain',name: '报考专业'},{key: 'level',name: '报考级别'},{key: 'create_at',name: '报名时间'},{key: 'apply_no',name: '报名编号'},
                {key: 'id_type',name: '证件类型'},{key: 'id_number',name: '证件号码'},{key: 'room',name: '考场'},{key: 'user_organ_name',name: '负责报名机构'},{key: 'user_name',name: '负责报名老师'},
                {key: 'status_name',name: '审核状态'},{key: 'plan_name',name: '当前进度'},{key: 'postpone_name',name: '是否存在缺考顺延'}],
            isLoading: false,
            totalNumber: 0,
            currentPage: 1,
            pageSize: 50,
        }
        
    },
    mounted(){
        this.queryInfo()
    },
    
    methods: {
      handleCurrentChange(val) {
          this.queryInfo(val)
      },
      //查询考生信息接口
      queryInfo(pn){
          this.$store.commit('signup/setPage',{})
          this.$store.commit('signup/setSignList',[])
          const {domain,name,level,id_type,id_number,status,plan,postpone,organ_name,teacher_name,signTime} = this
          const start_time = util.filterDate(signTime[0])
          const end_time = util.filterDate(signTime[1])
          pn = pn || 1
          this.isLoading = true
          let signList = []
          this.$axios({
              url : '/apply/list',
              method: 'post',
              data: {domain,name,level,id_type,id_number,status,plan,postpone,organ_name,teacher_name,start_time,end_time,pn}
          }).then(res=> {
              console.log(res);
              console.log('获取报名列表数据',res);
              if(res && !res.error) {
                  let data = res.data.list
                //   this.signList = []
                  if(data.length > 0){
                      data.forEach(item=> {
                          util.flatData(item).then(list=> {
                              if(list.examsite1) {
                                  list.room1 = '考场1' + list.examsite1.address + '(' + list.examsite1.room + ')；'
                              }
                              if(list.examsite2) {
                                  list.room2 = '考场2' + list.examsite2.address + '(' + list.examsite2.room + ')'
                              }
                              if(list.examsite1 && !list.examsite2) {
                                  list.room = list.room1
                              }
                            if(list.examsite1 && list.examsite2) {
                                  list.room = list.room1 + list.room2
                              }
                              this.verifyOptions.forEach(item=> {
                                  if(list.status == item.value) {
                                      list.status_name = item.label
                                  }
                              })
                              this.progressOptions.forEach(item=> {
                                  if(list.plan == item.value) {
                                      list.plan_name = item.label
                                  }
                              })
                              this.islostTestOptions.forEach(item=> {
                                  if(list.postpone == item.value) {
                                      list.postpone_name = item.label
                                  }
                              })
                              signList.push(list)
                          })
                      })
                  }

                // this.$store.commit('signup/setPage',res.data.page)
                this.totalNumber = res.data.page.total
                this.currentPage = res.data.page.pn
                this.pageSize = res.data.page.limit
                this.$store.commit('signup/setSignList',signList)
              }
              this.isLoading = false
              console.log('查询到的考生数据',signList);
          }).catch(err=> {
              console.log(err);
              this.isLoading = false
          })
      },
      //查看单个考生详情
      signDetail(scope){
          this.$router.push({
              name: 'signInfo',
              params: {
                  id: scope.row.id,
                  status: scope.row.status,
                  plan: scope.row.plan,
                  postpone: scope.row.postpone,
                  domain: scope.row.domain
              }
          })
      },
      
      //改变报考专业对应报考级别
      changeSelect: function(val) {
        this.levelOptions = [{key: '全部',value:''}]
        this.domainOptions.forEach(item=> {
            if(item.key == val) {
                for(let i =0;i<item.value.length;i++) {
                    this.levelOptions.push({key: item.value[i],value:item.value[i]})
                }
            }
        })
      } 
    },
    computed: {
        ...mapState('auth',{
            domainOptions: state=> state.domainOptions,
            certificate: state=> state.certificate,
        }),
        ...mapState('signup',{
            signList: state=> state.signList,
            page: state=> state.page
        })
    }
}
</script>



<style lang="scss" scoped>
.container {
    padding-left: 20px;
    margin-top: 20px;
    font-size: 16px;
    .queryInfo {
        .el-row {
            margin-top: 20px;
            p {
                word-break:keep-all;
                white-space:nowrap;
                text-align: center;
                vertical-align: center;
                line-height: 40px;
            }
            .el-col {
                display: flex;
            }
        }
        .name{
            display: inline-block;
        }
    }
    .download {
        margin-top: 40px;
    }
    .queryResult {
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-top: 40px;
        .el-header {
            display: flex;
            align-items: flex-end;
            padding-left: 0;
        }
        .el-main {
            padding-left: 0;
        }
    }
}
</style>