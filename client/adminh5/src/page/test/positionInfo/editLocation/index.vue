<template>
    <div class="container">
        <div class="header">
            <el-row>
                <el-col :span="3" :push="1"><el-button type="primary" @click="downloadTable">批量下载报名表</el-button></el-col>
                <el-col :span="3" :push="1"><el-button type="primary" @click="downloadCard">批量下载准考证</el-button></el-col>
                <el-col :span="3" :push="1"><el-button type="primary" @click="downloadPic">批量下载照片</el-button></el-col>
                <el-col :span="3" :push="1"><el-button type="primary" @click="outputTable">导出考级名单报名表</el-button></el-col>
                <el-col :span="2" :push="8"><el-button type="primary" @click="sort">考生排序</el-button></el-col>
                <el-col :span="2" :push="8"><el-button type="primary" @click="addExaminee = true">添加考生</el-button></el-col>
            </el-row>
        </div>
         <!-- 考生排序弹出层 -->
        <el-dialog :before-close="closeSort" title="考生排序" width="30%" :visible="sortExaminee">
            <span>(按住拖动进行排序)</span>
                <draggable element="ul" v-model="sortData">
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
                                    <el-option v-for="item in domainOptions" :key="item.key" :label="item.key" :value="item.label"> </el-option>
                                </el-select>
                            </el-col>
                        </el-row>
                        <el-row :gutter="40">
                            <el-col :span="6"><p>报考级别：</p>
                                <el-select v-model="level" placeholder="全部">
                                    <el-option v-for="item in levelOptions" :key="item.key" :label="item.key" :value="item.value"> </el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="8"><p>负责报名机构：</p>
                                <el-input v-model="organ_name" class="" placeholder="报名机构" clearable></el-input>
                            </el-col>
                            <el-col :span="6">
                                <p>负责报名老师：</p>
                                <el-input v-model="teacher_name" class="" placeholder="老师姓名" clearable></el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-button type="primary" @click.prevent="queryInfo" style="width: 80%">筛选</el-button>
                            </el-col>
                        </el-row>
                    </div>
                    <el-container class="queryResult">
                        <table-data :isPagination="true" :isSelected="true" :currentPage="currentPage" :totalNumber="totalNumber" :pageSize="pageSize" :head="headdata" :tableData="queryData" 
                        @handleCurrentChange="handleCurrentChange"  @handleSelectionChange="handleSelectionChange" :loadingTable="loadingExam"></table-data>
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
                <el-button @click="deleteexaminee">保存</el-button>
            </span>
        </el-dialog>


        <table-data :head="head" :tableData="examineeData" :isOption="'true'" :isDeleteTable="'true'" :deleteTableName="'删除'" @deleteInfo="deleteInfo" :loadingTable="isLoading">
        </table-data>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import tableData from '@/page/common/tableData'
import util from '@/util/util'
import Auth from '@/util/auth'
export default {
    data(){
        return {
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
            headdata: [{key: 'name',name: '姓名'},{key: 'id_number',name: '证件号码'},{key: 'domain',name: '报考专业'},{key: 'level',name: '报考级别'},
                {key: 'user_organ_name',name: '负责报名机构'},{key: 'user_name',name: '负责报名老师'}],
            head: [{key: 'sort',name: '考生排位号'},{key: 'apply_name',name: '考生姓名'},{key: 'apply_id_number',name: '证件号码'},{key: 'apply_domain',name: '报考专业'},
                {key: 'apply_level',name: '报考级别'},{key: 'apply_user_organ_name',name: '负责报名机构'},{key: 'apply_user_name',name: '负责报名老师'}],
            apply_id_arr: [],
            exam_site_id: '',
            exam_id: '',
            loadingExam: false,
            loadingInfo: false,
        }
    },
    components: {
        draggable,
        tableData
    },
    created() {
        document.body.ondrop = function (event) {
            event.preventDefault();
            event.stopPropagation();
        }
    },
    mounted(){
        this.getExamineeInfo()
        if(this.$route.params) {
            this.exam_id  = this.$route.params.exam_id
            this.exam_site_id  = this.$route.params.exam_site_id 
        }
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
            let sortData = []
            let examineeData = []
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
                            if(r.apply_user.type == '0') {
                                r.apply_user_name = r.apply_adviser
                            } else {
                                r.apply_user_name = r.apply_user.name
                            }
                            r.apply_user_organ_name = r.apply_user.organ_name
                            examineeData.unshift(r)
                            sortData.unshift({name: r.apply_name,id:r.id,sort: r.sort})
                            // this.sortData = sortData
                            this.$set(this,'sortData',sortData)
                            this.examineeData = examineeData
                        })
                    })
                } else {
                   this.$message.warning(res.msg)
                }
                this.isLoading = false
            }).catch(err=> {
                console.log(err)
                this.isLoading = false
            })
        },
        //批量下载报名表
        downloadTable: function(){
            let token = Auth.hasToken()
            let exam_site_id = this.exam_site_id
            let url = `https://www.hnyskj.net/adminapi/download/bm?token=${token}&exam_site_id=${exam_site_id}`
            let link = document.createElement('a')
            link.style.display = 'none'
            link.href = url
            document.body.appendChild(link)
            link.click()
        },
        //批量下载准考证
        downloadCard: function(){
            let token = Auth.hasToken()
            let exam_site_id = this.exam_site_id
            let url = `https://www.hnyskj.net/adminapi/download/kz?token=${token}&exam_site_id=${exam_site_id}`
            let link = document.createElement('a')
            link.style.display = 'none'
            link.href = url
            document.body.appendChild(link)
            link.click()
        },
        //批量下载照片
        downloadPic: function(){
            let token = Auth.hasToken()
            let exam_site_id = this.exam_site_id
            let url = `https://www.hnyskj.net/adminapi/download/zp?token=${token}&exam_site_id=${exam_site_id}`
            let link = document.createElement('a')
            link.style.display = 'none'
            link.href = url
            document.body.appendChild(link)
            link.click()
        },
        //到处考级名单报名表
        outputTable: function(){
            let token = Auth.hasToken()
            let exam_site_id = this.exam_site_id
            let url = `https://www.hnyskj.net/adminapi/download/site-apply-list?token=${token}&id=${exam_site_id}`
            let link = document.createElement('a')
            link.style.display = 'none'
            link.href = url
            document.body.appendChild(link)
            link.click()
        },


        //考生排序系列操作*****
        saveSort: function(){
            const exam_site_id  = this.$route.params.exam_site_id
            const id_arr = []
            this.sortData.forEach(item=> {
                id_arr.push(item.id)
            })
            this.$axios({
                url: '/examinee/sort',
                method: 'post',
                data: {exam_site_id,id_arr }
            }).then(res=> {
                console.log('考生排序结果',res)
                if(res && !res.error) {
                    this.getExamineeInfo()
                }
                this.sortExaminee = false
            }).catch(err=> {
                console.log(err);
                this.sortExaminee = false
            })
        },
        closeSort: function(){
            this.sortExaminee = false
        },
        cancelSort: function(){
           this.sortExaminee = false
        },
        sort: function(){
            this.sortData.sort(compare('sort'))
            this.sortExaminee = true
        },
        compare: function(sort){
            return function(a,b){
                const v1 = a[sort] 
                const v2 = b[sort]
                return v1-v2
            }
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
                    this.$message.success(res.msg)
                    this.getExamineeInfo()
                    this.queryData = []
                    this.addExaminee = false
                } else {
                    this.$message.warning(res.msg)
                    this.addExaminee = false
                }
                
            }).catch(err=> {
                console.log(err);
                this.addExaminee = false
            })
        },
        //*************选择考生 */
        handleSelectionChange: function(val) {
            const apply_id_arr = []
            val.forEach(item=> {
                apply_id_arr.push(item.id)
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
            this.loadingExam = true
            pn = pn || 1
            const {name,domain,level,id_number,organ_name,teacher_name,exam_id} = this
            let exam = []
            this.$axios({
                url: '/examinee',
                method: 'post',
                data: {name,domain,level,id_number,organ_name,teacher_name,exam_id,pn}
            }).then(res=> {
                if(res && !res.error) {
                    const list = res.data.list
                    list.forEach(item=> {
                        util.flatData(item).then(l=> {
                            if(l.user_type == '0') l.user_name = l.adviser
                            exam.push(l)
                        })
                    })
                    this.queryData = exam
                    this.pageSize = res.data.page.limit
                    this.totalNumber = res.data.page.total
                    this.currentPage = res.data.page.pn
                }else {
                    this.$message.warning(res.msg)
                }
                this.loadingExam = false
            }).catch(err=> {
                console.log(err);
                this.loadingExam = false
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
        deleteexaminee: function(){
            this.$message.warning('开发中')
            this.deleteExaminee = false
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
