<template>
    <div class="inform-object">
        <table-data :head="head" :tableData="informobject" :addInformData="addInformData" isEdit="'true'" :editName="editName" :isDeleteTable="'true'" :deleteTableName="deleteTableName"
         @editOption="editOption" @deleteInfo="deleteInfo" :editType="editType" :loadingTable="loadingInformTable" :loadingAddInformTable="loadingAddInformTable" 
          @saveAddInform="saveAddInform" @close="close" @getRowKey="getRowKey">
        </table-data>
        <!-- 添加通知对象弹出层 -->
        <el-dialog title="添加通知对象" :before-close="close" :visible.sync="dialogVisible" width="60%" :center="true" :lock-scroll="false" class="dialog-container">
            <div class="inform-header">
                <el-row :gutter="40">
                    <el-col :span="8"><p>考生姓名：</p><el-input v-model="name" class="" placeholder="考生姓名" clearable></el-input></el-col>
                    <el-col :span="8"><p>报考专业：</p>
                        <el-select v-model="domain" placeholder="全部" @change="changeSelect">
                            <el-option v-for="item in domainOptions" :key="item.key" :label="item.key" :value="item.key"> </el-option>
                        </el-select>
                    </el-col>
                    <el-col :span="8" :offset="0"><p>报考级别：</p>
                        <el-select v-model="level" placeholder="全部">
                            <el-option v-for="item in levelOptions" :key="item.value" :label="item.value" :value="item.value"> </el-option>
                        </el-select>
                    </el-col>
                </el-row>
                <el-row :gutter="40">
                    <el-col :span="8">
                        <p>报名时间：</p>
                        <el-date-picker v-model="signTime" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                    </el-col>
                    <el-col :span="4" :offset="2">
                        <el-button type="primary" @click.prevent="chooseInformObject">筛选</el-button>
                    </el-col>
                </el-row>
            </div>
            <table-data :isPagination="'true'" :currentPage="currentPage" :pageSize="pageSize" :totalNumber="totalNumber" 
            :isSelected="'true'" :tableData="addInformData" :head="informHead" :loadingTable="loadingAddInformTable" @handleSelectionChange="handleSelectionChange"></table-data>
            <span slot="footer">
                <el-button @click="cancel">取消</el-button>
                <el-button @click="saveAddInform">保存</el-button>
            </span>
        </el-dialog>
    </div>
</template>
<script>
import tableData from '../../../common/tableData'
import util from '@/util/util'
export default {
    data(){
        return{
            head: [{key: 'uid',name: '编号'},
                {key: 'name',name: '考生姓名'},
                {key: 'domain',name: '报考专业'},
                {key: 'level',name: '报考级别'},
                {key: 'create_at',name: '报名时间'}
            ],
            addInformData: [], //获取通知对象全部列表
            editName: '添加通知对象',
            loadingInformTable: false,  // 获取已添加通知对象列表状态
            loadingAddInformTable: false,  //获取通知对象全部列表状态
            deleteTableName: '删除',
            editType: '',
            dialogVisible: false,
            name: '',
            domain: [],
            level: '',
            levelOptions: [],
            signTime: [],
            currentPage: 1,
            pageSize: 50,
            totalNumber: 0,
            loadingTable: false,
            informHead: [{key: 'uid',name: '编号'},{key: 'name',name: '考生姓名'},{key: 'domain',name: '报考专业'},
                {key: 'level',name: '报考级别'},{key: 'create_at',name: '报名时间'}],
            uid_arr: [],
            readyinforobject: [],
            

        }
    },
    components: {
        tableData
    },
    mounted(){
        if(this.$route.params.inform) {
            this.informobject = this.$route.params.inform
        }
    },
    methods: {
        //筛选通知对象
        chooseInformObject:function(pn){
            const {name,domain,level} = this
            pn = pn || 1
            const start_time = util.filterDate(this.signTime[0]) || ''
            const end_time = util.filterDate(this.signTime[1]) || ''
            this.$axios({
                url: '/inform/object',
                method: 'post',
                data: { name,domain,level,start_time,end_time,pn }
            }).then(res=> {
                console.log('获取通知对象列表数据',res);
                if(res && !res.error) {
                    this.addInformData = res.data.list
                    this.totalNumber = res.data.page.total
                    this.pageSize = res.data.page.limit
                    this.currentPage = res.data.page.pn
                }
            }).catch(err=>{
                console.log(err);
            })
        },
        //取消添加通知对象操作
        cancel: function(){
            this.dialogVisible = false
            this.$store.commit('informobject/setaddInformobject',this.informobject)
            console.log('*******************',this.informobject);
            this.uid_arr = []
            this.informobject.forEach(item=> {
                this.uid_arr.push(item.uid)
            })
            this.$router.push({
                name: 'publishInform',
                params: {
                    uid_arr: this.uid_arr,
                    type: this.type,
                    inform: this.informobject
                }
            })
        },
        //翻页功能
        handleCurrentChange: function(val){
            this.chooseInformObject(val)
        },
        //多选操作
        handleSelectionChange: function(val){
            console.log(val);
            
            this.readyinforobject = val
        },
        //添加至通知对象列表操作
        saveAddInform: function(){
            this.$store.commit('informobject/setaddInformobject',this.readyinforobject)
            this.dialogVisible = false
            this.uid_arr = []
            this.readyinforobject.forEach(item=>{
                this.uid_arr.push(item.uid)
            })
            this.$router.push({
                name: 'publishInform',
                params: {
                    uid_arr: this.uid_arr,
                    type: this.type,
                    inform: this.readyinforobject
                }
            })
        },
        //删除通知对象
        deleteInfo:function(scope){
            const inform_id = this.$route.params.inform_id || ''
            const informobject = this.$store.state.informobject.informobjectdata
            const list = []
            console.log(inform_id);
            if(inform_id) {
                const uid = scope.row.uid
                this.$axios({
                    url: '/inform/object/delete',
                    method: 'post',
                    data: {uid,inform_id}
                }).then(res=>{
                    console.log('删除通知对象响应',res);
                    if(res && !res.error){
                        informobject.forEach(item=>{
                            if(item.uid !== uid) {
                                list.push(item)
                            }
                        })
                        this.$store.commit('informobject/setInformobject',list)
                    }
                }).catch(err=> {
                    console.log(err);
                })
            }
        },
        //获取专业列表
        getOption(){
            this.$axios({
                url: '/option',
                method: 'post',
                data: {}
            }).then(res=> {
                console.log('获取基本选项',res)
                if(res && !res.error) {
                    let { major } = res.data
                    let majors = []
                    for (let mj in major) {
                        majors.push({ value: mj, text: mj, levels: major[mj] })
                    }
                    this.options = majors
                    console.log('majors',majors);
                }
               
            }).catch(err=> {
                console.log(err);
            })
        },
        //子组件传递的方法
        editOption: function(){
            this.dialogVisible = true
            this.getOption()
        },
        close: function(){
            this.dialogVisible = false
            this.$store.commit('informobject/setaddInformobject',this.informobject)
            console.log('*******************',this.informobject);
            this.uid_arr = []
            this.informobject.forEach(item=> {
                this.uid_arr.push(item.uid)
            })
            this.$router.push({
                name: 'publishInform',
                params: {
                    uid_arr: this.uid_arr,
                    type: this.type,
                    inform: this.informobject
                }
            })
        },
        //改变选择框时触发
        changeSelect: function(val){
            this.levelOptions = []
           this.domainOptions.forEach(item=> {
              if(item.key == val) {
                  for(let i =0;i<item.value.length;i++) {
                      this.levelOptions.push({key: item.value[i],value:item.value[i]})
                  }
              }
          })
        },
        //避免分页时选中数据重新请求后台
        getRowKey(row){
            return row.uid
        }
    },
    computed: {
        informobject(){
            return this.$store.state.informobject.addinformobjectdata
        },
        type(){
            return this.$store.state.informobject.type
        },
            //报考专业及报考级别
        domainOptions() {
            return this.$store.state.auth.domainOptions
        },
    }
}
</script>


<style lang="scss" scoped>
    .inform-object {
        .dialog-container {
            .inform-header{
                .el-row {
                    margin-top: 20px;
                    .el-col {
                        display: flex;
                        p {
                            word-break: break-all;
                            white-space:nowrap;
                            line-height: 40px;
                        }
                    }
                }
            }
        }
    }
</style>


