<template>
    <div class="inform-object">
        <table-data :isPagination="'true'" :currentPage="cp" :pageSize="ps" :totalNumber="tn" :head="head" :tableData="pageinformobject" isEdit="'true'"  :isOption="true" 
        :editName="editName" :isDeleteTable="'true'" :deleteTableName="deleteTableName" @editOption="editOption" @deleteInfo="deleteInfo" :loadingTable="loadingInformTable" 
        @handleCurrentChange="handlePage">
        </table-data>
        <div class="button">
            <el-col ><el-button style="width:10%;" @click="confirm" type="primary">确定</el-button></el-col>
        </div>
        
        <!-- 添加通知对象弹出层 -->
        <el-dialog title="添加通知对象" :before-close="close" :visible.sync="dialogVisible" width="60%" :center="true" :lock-scroll="false" class="dialog-container">
            <div class="inform-header">
                <el-row :gutter="40">
                    <el-col :span="8"><p>考生姓名：</p><el-input v-model="name" class="" placeholder="考生姓名" clearable></el-input></el-col>
                    <el-col :span="8"><p>报考专业：</p>
                        <el-select v-model="domain" placeholder="全部" @change="changeSelect">
                            <el-option v-for="item in domainOptions" :key="item.key" :label="item.key" :value="item.label"> </el-option>
                        </el-select>
                    </el-col>
                    <el-col :span="8" :offset="0"><p>报考级别：</p>
                        <el-select v-model="level" placeholder="全部">
                            <el-option v-for="item in levelOptions" :key="item.key" :label="item.key" :value="item.value"> </el-option>
                        </el-select>
                    </el-col>
                </el-row>
                <el-row :gutter="40">
                    <el-col :span="8">
                        <p>报名时间：</p>
                        <el-date-picker v-model="signTime" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                    </el-col>
                    <el-col :span="4" :offset="2">
                        <el-button type="primary" style="width:70%;" @click.prevent="chooseInformObject">筛选</el-button>
                    </el-col>
                </el-row>
            </div>
            <table-data :isPagination="'true'" :currentPage="currentPage" :pageSize="pageSize" :totalNumber="totalNumber" :isSelected="'true'" :tableData="addInformData" 
            :head="informHead" :loadingTable="loadingAddInformTable" @handleSelectionChange="handleSelectionChange"></table-data>
            <span slot="footer">
                <el-button @click="cancel" style="width:10%;">取消</el-button>
                <el-button @click="saveAddInform" type="primary" style="width:10%;">保存</el-button>
            </span>
        </el-dialog>

    </div>
</template>
<script>
import tableData from '@/components/common/tableData'
import util from '@/util/util'
export default {
    data(){
        return{
            head: [{key: 'uid',name: '编号'},{key: 'name',name: '考生姓名'},{key: 'domain',name: '报考专业'},{key: 'level',name: '报考级别'},{key: 'create_at',name: '报名时间'}],
            cp: 1,
            ps: 50,
            tn: 0,
            addInformData: [], //获取通知对象全部列表
            editName: '添加通知对象',
            loadingInformTable: false,  // 获取已添加通知对象列表状态
            loadingAddInformTable: false,  //获取通知对象全部列表状态
            deleteTableName: '删除',
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
            readyinforobject: [],
            pageinformobject: []
        }
    },
    components: {
        tableData
    },
    mounted(){
        this.pageinformobject = this.informobject
    },
    methods: {
        //点击确定返回上一页
        confirm: function(){
            this.$router.push({
                name: 'publishInform',
                params: {
                    type: this.type,
                    content: this.content
                }
            })
        },
        //筛选通知对象
        chooseInformObject:function(pn){
            const {name,domain,level} = this
            pn = pn || 1
            const start_time = util.filterDate(this.signTime[0]) || ''
            const end_time = util.filterDate(this.signTime[1]) || ''
            this.$axios({
                url: '/object',
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
        //翻页筛选通知对象
        handleCurrentChange: function(val){
            this.chooseInformObject(val)
        },
        //选择待添加通知对象
        handleSelectionChange: function(val){
            console.log(val);
            this.readyinforobject = val
        },
        //添加至通知对象列表操作
        saveAddInform: function(){
            this.$store.commit('informobject/setaddInformobject',this.readyinforobject)
            this.pageinformobject = this.readyinforobject
            const uid_arr = []
            this.readyinforobject.forEach(item=>{
                uid_arr.push(item.uid)
            })
            this.$store.commit('informobject/setAddUid',uid_arr)
            this.dialogVisible = false
        },
        close: function(){
            this.dialogVisible = false
        },
        //取消添加通知对象操作
        cancel: function(){
            this.dialogVisible = false
        },
        // ********************

        //通知对象列表翻页
        handlePage: function(val) {
            const length = this.tn
            const arr = this.informobject
            const offset = (val - 1)*this.ps
            if(offset + this.ps >= length) {
                this.pageinformobject = arr.splice(offset,length)
            } else {
                this.pageinformobject = arr.aplice(offset,offset + this.ps)
            }
        },
        //删除通知对象
        deleteInfo:function(scope){
            const inform_id = this.$route.params.inform_id || ''
            const informobject = this.informobject
            const list = []
            console.log(inform_id);
            if(inform_id) {
                const uid = scope.row.uid
                this.$axios({
                    url: '/object/delete',
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
                        this.$store.commit('informobject/setaddInformobject',list)
                    }
                }).catch(err=> {
                    console.log(err);
                })
            }
        },
        //子组件传递的方法
        editOption: function(){
            this.dialogVisible = true
            // this.getOption()
        },
        //改变选择框时触发
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
        //避免分页时选中数据重新请求后台
        // getRowKey(row){
        //     return row.uid
        // }
    },
    computed: {
        informobject(){
            return this.$store.state.informobject.addinformobjectdata.inform
        },
            //报考专业及报考级别
        domainOptions() {
            return this.$store.state.auth.domainOptions
        },
        tn(){
            return this.$store.state.informobject.addinformobjectdata.inform.length
        },
        type(){
            return this.$store.state.informobject.addinformobjectdata.type
        },
        content(){
            return this.$store.state.publishinfo.quillContent
        }
    }
}
</script>


<style lang="scss" scoped>
    .inform-object {
        .button{
            margin-top: 60px;
            margin-left: 40%;
            padding-bottom: 100px;
            .el-button {
                margin-left: 40px;
            }
        }
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


