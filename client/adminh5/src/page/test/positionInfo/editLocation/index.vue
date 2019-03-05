<template>
    <div class="container">
        <div class="header">
            <el-row>
                <el-col :span="3" :push="1"><el-button type="primary" @click="downloadTable">批量下载报名表</el-button></el-col>
                <el-col :span="3" :push="1"><el-button type="primary" @click="downloadCard">批量下载准考证</el-button></el-col>
                <el-col :span="3" :push="1"><el-button type="primary" @click="downloadPic">批量下载照片</el-button></el-col>
                <el-col :span="2" :push="8"><el-button type="primary" @click="sortExaminee = true">考生排序</el-button></el-col>
                <el-col :span="2" :push="8"><el-button type="primary" @click="addExaminee = true">添加考生</el-button></el-col>
            </el-row>
        </div>
         <!-- 考生排序弹出层 -->
        <el-dialog :before-close="closeSort" title="考生排序" width="30%" :visible="sortExaminee">
            <span>(按住拖动进行排序)</span>
                <draggable element="ul" v-model="sortData" @move="changeSort">
                    <transition-group>
                        <li v-for="item in sortData" :key="item.id">
                            {{item.name}}
                        </li>
                    </transition-group>
                </draggable>
            <span slot="footer">
                <el-button @click="cancelSort">取消</el-button>
                <el-button @click="saveSort">保存</el-button>
            </span>
        </el-dialog>

        <!-- 添加考生弹出层 -->
            <el-dialog title="添加考生" :before-close="closeSave" :visible="addExaminee" width="60%" :center="true" :lock-scroll="false">
                <div class="add-container">
                    <div class="queryInfo">
                        <el-row :gutter="40">
                            <el-col :span="8"><p>考生姓名：</p><el-input v-model="name" class="" placeholder="考生姓名" clearable></el-input></el-col>
                            <el-col :span="8"><p>证件号码：</p>
                                <el-input v-model="id_number" class="" placeholder="证件号码" clearable></el-input>
                            </el-col>
                            <el-col :span="8"><p>报考专业：</p>
                                <el-select v-model="domain" placeholder="全部">
                                    <el-option v-for="item in domainOptions" :key="item.key" :label="item.key" :value="item.value"> </el-option>
                                </el-select>
                            </el-col>
                        </el-row>
                        <el-row :gutter="40">
                            <el-col :span="8" :offset="0"><p>报考级别：</p>
                                <el-select v-model="level" placeholder="全部">
                                    <el-option v-for="item in levelOptions" :key="item.value" :label="item.value" :value="item.value"> </el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="8"><p>负责报名机构：</p>
                                <el-input v-model="organ_name" class="" placeholder="报名机构" clearable></el-input>
                            </el-col>
                            <el-col :span="6">
                                <p>负责报名老师：</p>
                                <el-input v-model="teacher_name" class="" placeholder="老师姓名" clearable></el-input>
                            </el-col>
                            <el-col :span="2">
                                <el-button type="primary" @click.prevent="queryInfo">筛选</el-button>
                            </el-col>
                        </el-row>
                    </div>
                    <el-container class="queryResult">
                        <table-data :isPagination="true" :currentPage="currentPage" :totalNumber="totalNumber" :pageSize="pageSize" :head="headdata" :tableData="queryData" 
                        @handleCurrentChange="handleCurrentChange"  @handleSelectionChange="handleSelectionChange"></table-data>
                    </el-container>
                </div>
                <span slot="footer">
                    <el-button @click="cancelSave">取消</el-button>
                    <el-button @click="saveAddExaminee">保存</el-button>
                </span>
            </el-dialog>
        <!-- 删除考生信息提示层 -->
        <el-dialog title="提示" :before-close="closeDelete" :visible="deleteExaminee" width="60%" :center="true" :lock-scroll="false">
                <p>删除该考生后，请及时安排该考生的考场信息并重新下载该考生的准考证和报名表</p>
                <span slot="footer">
                    <el-button @click="cancelDelete">取消</el-button>
                    <el-button @click="deleteExaminee">保存</el-button>
                </span>
            </el-dialog>
        <table-data :head="head" :tableData="examineeData" :isOption="'true'" :isDeleteTable="'true'" :deleteTableName="'删除'" @deleteInfo="deleteInfo" :isLoadingTable="isLoading">
        </table-data>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import util from '@/util/util'
export default {
    data(){
        return {
            head: [{key: 'sort',name: '考生排位号'},{key: 'apply_name',name: '考生姓名'},{key: 'apply_id_number',name: '证件号码'},{key: 'apply_domain',name: '报考专业'},
                    {key: 'apply_level',name: '报考级别'},{key: 'apply_user_organ_name',name: '负责报名机构'},{key: 'apply_user_name',name: '负责报名老师'}],
            examineeData: [],
            isLoading: false,
            sortExaminee: false,
            addExaminee: false,
            deleteExaminee: false,
            sortData: [],
            exam_id : '',
            name: '',
            domain: [],
            level: '',
            levelOptions: [],
            id_number: '',
            organ_name: '',
            teacher_name: '',
            currentPage: 1,
            totalNumber: 0,
            pageSize: 50,
            queryData: [],
            headdata: [{key: 'studentName',name: '姓名'},{key: 'professional',name: '证件号码'},{key: 'level',name: '报考专业'},{key: 'signTime',name: '报考级别'},
                {key: 'signNo',name: '负责报名机构'},{key: 'idCardType',name: '负责报名老师'}],
            apply_id_arr: []
        }
    },
    components: {
        draggable,
    },
    mounted(){
        this.getExamineeInfo()
        this.exam_id  = this.$route.params.exam_id 
    },
    computed: {
        domainOptions() {
            return this.$store.state.auth.domainOptions
        },
    },
    methods: {
        //获取考试
        getExamineeInfo(){
            this.isLoading = true
            const exam_site_id = this.$route.params.exam_site_id
            this.$axios({
                url : '/examinee/list',
                method: 'post',
                data: {exam_site_id}
            }).then(res=> {
                console.log('查询到的该考场考生信息',res);
                if(res && !res.error) {
                    let list = res.data.list
                    list.forEach(item=> {
                        util.flatData(item).then(r=> {
                            this.examineeData = r
                            let sortData = []
                            r.forEach(item=> {
                                sortData.push({name: item.apply_name,id:item.id})
                            })
                            this.sortData = sortData
                        })
                    })
                } else {
                   alert(res.msg)
                }
                this.isLoading = false
            }).catch(err=> {
                console.log(err)
                this.isLoading = false
            })
        },
        //批量下载报名表
        downloadTable: function(){
            this.$axios({
                url: '/download/bm',
                method: 'post',
                data: {}
            }).then(res=> {
                if(res && !res.error) {
                    console.log('批量下载报名表',res);
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        //批量下载准考证
        downloadCard: function(){
            this.$axios({
                url: '/download/kz',
                method: 'post',
                data: {}
            }).then(res=> {
                if(res && !res.error) {
                    console.log('批量下载准考证',res);
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        //批量下载照片
        downloadPic: function(){
            this.$axios({
                url: '/download/zp',
                method: 'post',
                data: {}
            }).then(res=> {
                if(res && !res.error) {
                    console.log('批量下载照片',res);
                }
            }).catch(err=> {
                console.log(err);
            })
        },


        //考生排序系列操作*****
        saveSort: function(){
            const exam_site_id  = this.$route.params.exam_site_id
            const id_arr = []
            this.$axios({
                url: '/examinee/sort',
                method: 'post',
                data: {exam_site_id,id_arr }
            }).then(res=> {
                if(res && !res.error) {
                    console.log('考生排序结果',res)
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        changeSort: function(evt){
            console.log(evt);
        },
        closeSort: function(){
            this.sortExaminee = false
        },
        cancelSort: function(){
           this.sortExaminee = false
        },

        //添加考生系列操作*****
        saveAddExaminee: function(){
            const exam_site_id  = this.$route.params.exam_site_id
            const apply_id_arr = this.apply_id_arr
            this.$axios({
                url: '/examinee/seat',
                method: 'post',
                data: {exam_site_id,apply_id_arr}
            }).then(res=> {
                if(res && !res.error) {
                    alert(res.msg)
                    this.getExamineeInfo()
                    this.addExaminee = false
                } else {
                    alert(res.msg)
                    this.addExaminee = false
                }
                
            }).catch(err=> {
                console.log(err);
                this.addExaminee = false
            })
        },
        handleSelectionChange: function(val) {
            const apply_id_arr = []
            val.forEach(item=> {
                apply_id_arr.push(item.apply_id)
            })
            this.apply_id_arr = apply_id_arr
        },
        closeSave: function(){
            this.addExaminee = false
        },
        cancelSave: function(){
           this.addExaminee = false
        },
        //**************/查询考生
        queryInfo(pn){
            pn = pn || 1
            const {name,domain,level,id_number,organ_name,teacher_name,exam_id} = this
            this.$axios({
                url: '/examinee',
                method: 'post',
                data: {name,domain,level,id_number,organ_name,teacher_name,exam_id,pn}
            }).then(res=> {
                if(res && !res.error) {
                    this.queryData = res.data.list
                    this.pageSize = res.data.page.limit
                    this.totalNumber = res.data.page.total
                    this.currentPage = res.data.page.pn
                }else {
                    alert(res.msg)
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        handleCurrentChange(val) {
            this.queryInfo(val)
        },

        //删除考生系列操作*****
        //删除考生信息
        deleteInfo(){
            this.deleteExaminee = true
        },
        deleteExaminee: function(){
            alert('开发中')
        },
        closeDelete: function(){
            this.deleteExaminee = false
        },
        cancelDelete: function(){
           this.deleteExaminee = false
        },
        // ***********************
        //改变选择框时触发
        changeSelect: function(val){
            this.levelOptions = [{key: '全部',value:''}]
            this.domainOptions.forEach(item=> {
                if(item.key == val) {
                    for(let i =0;i<item.value.length;i++) {
                        this.levelOptions.push({key: item.value[i],value:item.value[i]})
                    }
                }
            })
        },
    }
}
</script>

<style lang="scss" scoped>
    .container{
        margin-top: 100px;
        ul {
            li {
                width: 100%;
                height: 40px;
                line-height: 40px;
                border-radius: 4px;
                border-top: 1px solid #999999;
                border-left: 1px solid #999999;
                border-right: 1px solid #999999;
                text-align: center;
                &:last-child {
                    border-bottom: 1px solid #999999;
                }
                &:hover {
                    background-color: #eee;
                }
            }
        }
        .add-container {
            padding-left: 20px;
            margin-top: 20px;
            font-size: 16px;
            .queryInfo {
                .el-row {
                    margin-top: 20px;
                    display: flex;
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
            }
        }
    }
</style>
