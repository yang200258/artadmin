<template>
    <div class="inform-list">
        <div class="inform-header">
            <el-row>
                <el-col :span="4">
                    <p>通知类型：</p>
                    <el-select v-model="type">
                        <el-option v-for="item in filter" :key="item.value" :label="item.label" :value="item.value" ></el-option>
                    </el-select>
                </el-col>
                <el-col :span="6">
                    <p>发布日期：</p>
                    <el-date-picker v-model="publishInformDate" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                </el-col>
                <el-col :span="8">
                    <el-button type="primary" @click.prevent="queryInformList">查询</el-button>
                </el-col>
            </el-row>
        </div>
        <div class="inform-footer">
            <table-data :isDelete="'true'" :deleteName="'删除'" :isSelected="'true'" :head="informHead" :tableData="informListData" :isOption="'true'" :loadingTable="loadingInformList" 
            :isEditTable="'true'" :isDeleteTable="'true'" :editTableName="'编辑'" :deleteTableName="'删除'" @editInfo="editInfo" @deleteInfo="deleteInfo" 
            @handleCurrentChange="handleCurrentChange" @handleSelectionChange="handleSelectionChange" @deleteOption="deleteOption">
            </table-data>
        </div>
    </div>
</template>

<script>
import util from '@/util/util'
export default {
    data(){
        return{
            type: '',
            filter: [{value: '',label: '全部'},{value: '1',label: '成绩查询'},{value: '2',label: '准考证领取'},{value: '3',label: '考场查询'},
            {value: '4',label: '考试报名'},{value: '5',label: '大赛通知'},{value: '6',label: '定向通知'}],
            publishInformDate: [],
            informHead: [{key:'id',name: '通知编号'},{key:'type',name: '通知类型'},{key:'content',name: '通知详情'},{key:'create_at',name: '发布时间'}],
            informListData: [],
            loadingInformList: false,
            deleteid: [],
        }
    },
    methods: {
        //查询通知列表
        queryInformList: function(pn){
            this.loadingInformList = true
            pn = pn || 1
            const start_time = util.filterDate(this.publishInformDate[0])
            const end_time = util.filterDate(this.publishInformDate[1])
            const type = this.type
            this.$axios({
                url: '/inform/list',
                method: 'post',
                data: {type,start_time,end_time,pn }
            }).then(res=>{
                console.log('查询通知列表',res);
                if(res && !res.error) {
                    res.data.list.forEach(item=> {
                        item.type = res.data.filter[item.type]
                    })
                    this.$store.commit('informobject/setFilter',res.data.filter)
                    this.informListData = res.data.list
                    this.loadingInformList = false
                }
            }).catch(err=>{
                console.log(err);
                this.loadingInformList = false
            })
        },
        //编辑通知对象
        editInfo: function(scope){
            const filters = this.$store.state.informobject.filter
            const type = filters.indexOf(scope.row.type)
            if(type) this.$store.commit('informobject/setType',type)
            this.$store.commit('publishinfo/setquillContent',scope.row.content)
            this.$store.commit('informobject/setEditUid',scope.row.uid_arr)
            this.$store.commit('informobject/setInformId',scope.row.id)
            this.$router.push({
                name: 'editInform',
            })
        },
        //删除通知对象
        deleteInfo:function(scope){
            this.loadingInformList = true
            this.$axios({
                url: '/inform/delete',
                method: 'post',
                data: {id:[scope.row.id]}
            }).then(res=> {
                if(res && !res.error) {
                    //在通知列表中删除
                    this.queryInformList()
                    this.loadingInformList = false
                }
            }).catch(err=> {
                console.log(err);
                this.loadingInformList = false
            })
        },
        //翻页功能
        handleCurrentChange:function(val){
            this.queryInformList(val)
        },
        //批量删除通知对象
        handleSelectionChange: function(val){
             this.deleteid = []
            if(val) {
                val.forEach(item=> {
                    this.deleteid.push(item.id)
                })
            }
        },
        //批量删除操作
        deleteOption: function(){
            this.$axios({
                url: '/inform/delete',
                method: 'post',
                data: {id:this.deleteid}
            }).then(res=> {
                if(res && !res.error) {
                    //在通知列表中删除
                    this.queryInformList()
                    this.loadingInformList = false
                }
            }).catch(err=> {
                console.log(err);
                this.loadingInformList = false
            })
        }
    },
    computed: {
        filters(){
            return this.$store.state.informobject.filter
        },
    },
    mounted(){
        this.queryInformList()
    }
}
</script>


<style lang="scss" scoped>
    .inform-list{
        font-size: 16px;
        .inform-header{
            margin-top: 40px;
            margin-left: 20px;
            .el-row{
                .el-col{
                    display: flex;
                    line-height: 40px;
                    p{
                        word-break: break-all;
                    }
                }
            }
        }
        .inform-footer{
            margin-top: 40px;
        }
    }
</style>


