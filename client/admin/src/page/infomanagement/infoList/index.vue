<template>
    <div class="container">
        <div class="header">
            <el-row :gutter="40">
                <el-col :span="4" class="infoType">
                    <p>信息分类：</p>
                    <el-select v-model="type" placeholder="全部">
                        <el-option v-for="item in infoTypeOptions" :key="item.id" :label="item.name" :value="item.id"></el-option>
                    </el-select>
                </el-col>
                <el-col :span="4" class="title">
                    <p>标题：</p>
                    <el-input placeholder="请输入标题" v-model="title"></el-input>
                </el-col>
                <el-col :span="4" class="infoStatus">
                    <p>状态：</p>
                    <el-select v-model="status" placeholder="全部">
                        <el-option v-for="item in infoStatusOptions" :key="item.value" :label="item.label" :value="item.value"></el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row :gutter="40"> 
                <el-col :span="8">
                    <p>发布日期：</p>
                    <el-date-picker v-model="publishDate" type="daterange" range-separator="至" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                </el-col>
                <el-col :span="8">
                    <el-button type="primary" style="width:120px" @click.prevent="queryInfoData">查询</el-button>
                </el-col>
            </el-row>
        </div>
        <table-data :head="head" :isPagination="'true'" :currentPage="currentPage" :pageSize="pageSize" :totalNumber="totalNumber" :isEdit="'true'" isDelete="'true'" isSelected="'true'" :loadingTable="loadingTable"
        :tableData="infoData" :editName="'信息分类管理'" :deleteName="'删除'" :isOption="'true'" :isEditTable="'true'" :editTableName="'编辑'" :isDeleteTable="'true'" :deleteTableName="'删除'"  @handleCurrentChange="handleCurrentChange"
        @editInfo="editInfo" @deleteInfo="deleteInfo" @editOption="editOption" @deleteOption="deleteOption" @handleSelectionChange="handleSelectionChange" @getRowKey="getRowKey">
         </table-data>
        <el-dialog title="分类管理" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
            <el-tag class="category-container" v-for="item in category" :key="item.id" closable @close="closeTag(item.id)" ref="categaryName">
                <span>{{item.name}}</span>
                <div class="el-icon-edit" @click="editCategaryName(item)"></div>
                <!-- <div class="el-icon-delete"></div> -->
            </el-tag>
            <el-input class="input-new-tag" v-if="inputVisible" v-model="addcategoryname" ref="saveTagInput" size="small" @keyup.enter.native="handleInputConfirm" @blur="handleInputConfirm">
            </el-input>
            <el-button v-else class="button-new-tag" size="small" @click="addCategory">添加分类</el-button>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
            </span>
            <!-- 编辑分类信息内层dialog -->
            <el-dialog width="30%" title="编辑" :visible.sync="innerVisible" append-to-body>
                <el-input class="input-new-tag" v-model="editcategary.name" ref="editTagInput" size="small" @keyup.enter.native="editCategary"></el-input>
            </el-dialog>
        </el-dialog>
            
    </div>
</template>

<script>
import util from '@/util/util'
export default {
    data(){
        return {
            category: [],
            categoryname: '',
            innerVisible: false,
            inputVisible: false,
            editcategary: {},
            dialogVisible: false,
            infoTypeOptions: [],
            type: '',
            status: '',
            infoStatusOptions: [
                {
                value: '1',
                label: '已发布'
                }, {
                value: '2',
                label: '草稿'
                }
            ],
            publishDate: [],
            pageSize: 0,
            totalNumber: 0,
            infoData: [],
            currentPage: 1,
            head: [
                {
                    key: 'id',
                    name: '信息编号'
                },
                {
                    key: 'infoType',
                    name: '信息分类'
                },
                {
                    key: 'title',
                    name: '标题'
                },
                {
                    key: 'cover_url',
                    name: '封面图'
                },
                {
                    key: 'status',
                    name: '状态'
                },
                {
                    key: 'create_at',
                    name: '发布时间'
                }
            ],
            loadingTable: false,
            title: '',
            count: 99,
            addcategoryname: '',
            deleteid: []
        }
    },
    mounted(){
        this.getTypeList()
    },
    methods: {
        //点击弹出添加信息分类
        addCategory: function(){
            this.inputVisible = true;
            this.$nextTick( ()=> {
                this.$refs.saveTagInput.$refs.input.focus();
            });
        },
        //点击确定或回车添加信息分类
        handleInputConfirm: function(){
            let addcategoryname = this.addcategoryname;
            if (addcategoryname) {
                this.$axios({
                    url: '/msg/category/add',
                    method: 'post',
                    data: {name: addcategoryname}
                }).then(res=>{
                    console.log('添加信息分类响应',res);
                    if(res && !res.error) {
                        //重新获取信息分类列表
                        this.getTypeList()
                        this.category.push({id: this.count++,name: addcategoryname})
                        this.inputVisible = false;
                        this.addcategoryname = '';
                    }
                }).catch(err=> {
                    console.log(err);
                })
            }
            this.inputVisible = false
        },
        //删除信息分类
        closeTag: function(id){
            console.log(id);
            this.$axios({
                url: '/msg/category/delete',
                method: 'post',
                data: {id: id}
            }).then(res=> {
                console.log(res);
                if(res && !res.error) {
                    util.delIdArray(this.category,id)
                } else {
                    alert(res.msg)
                }
            }).catch(err=>{
                console.log(err);
            })
        },
        
        //编辑分类信息名称
        editCategaryName: function(item){
            this.innerVisible = true
            this.editcategary = item
            this.$nextTick( ()=> {
                this.$refs.editTagInput.$refs.input.focus();
            });
        },
        //回车完成信息分类编辑
        editCategary: function(){
            this.$axios({
                url: '/msg/category/edit',
                method: 'post',
                data: this.editcategary
            }).then(res=> {
                console.log('编辑分类信息响应',res);
                if(res && !res.error) {
                    this.innerVisible = false
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        handleSizeChange(val) {
            console.log(`每页 ${val} 条`);
        },
        handleCurrentChange(val) {
            console.log(`当前页: ${val}`);
        },
        //查询信息列表
        queryInfoData: function(pn){
            const {title,status,publishDate,type } = this
            this.loadingTable = true
            pn = pn || 1
            const start_time = util.filterDate(publishDate[0])
            const end_time = util.filterDate(publishDate[1])
            this.$axios({
                url : '/msg/list',
                method: 'post',
                data: {cid:type,title,status,start_time,end_time,pn }
            }).then(res=> {
                console.log('查询信息列表数据',res);
                if(res && !res.error) {
                    res.data.list.forEach(item=> {
                        item.status = (item.status = 1) ? '已发布' : '草稿'
                        this.infoTypeOptions.forEach(type=> {
                            if(type.id == item.cid) {
                                item.infoType = type.name
                            }
                        })
                    })
                    this.infoData = res.data.list
                    this.totalNumber = res.data.page.total
                    this.pageSize = res.data.page.limit
                    this.currentPage = res.data.page.pn
                    this.is_end = res.data.page.is_end
                }
                this.loadingTable = false
            }).catch(err=> {
                this.loadingTable = false
                console.log(err);
                // this.loadingTable = false
            })
        },
        //打开信息分类管理页面
        editOption: function(){
            this.dialogVisible = true
            this.$axios({
                url: '/msg/category/list',
                method: 'post',
                data: {}
            }).then(res=> {
                console.log('信息分类数据',res);
                if(res && !res.error) {
                    this.category = res.data
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        //批量删除信息
        deleteOption: function(){
            this.$axios({
                url: '/msg/delete',
                method: 'post',
                data: {id:this.deleteid}
            }).then(res=>{
                if(res && !res.error){
                    this.queryInfoData()
                }
                this.deleteid = []
            }).catch(err=> {
                console.log(err);
                this.deleteid = []
            })
        },
        //编辑信息
        editInfo: function(scope){
            this.$router.push({
                name: 'editInfo',
                params: {
                    id: scope.row.id,
                    url: scope.row.cover_url
                }
            })
        },

        //删除信息
        deleteInfo: function(scope){
            const id = scope.row.id
            this.$axios({
                url: '/msg/delete',
                method: 'post',
                data: {id:[id]}
            }).then(res=>{
                if(res && !res.error){
                    this.queryInfoData()
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        //获取信息分类
        getTypeList: function(){
            this.$axios({
                url: '/msg/category/list',
                method: 'post',
                data: {}
            }).then(res=>{
                console.log('获取到的信息分类列表',res);
                if(res && !res.error) {
                    // this.infoTypeOptions = res.data
                    this.$set(this,'infoTypeOptions',res.data)
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        //关闭信息管理窗口
        handleClose: function(){
            this.dialogVisible = false
        },
        //翻页功能
        handleCurrentChange: function(val){
            this.queryInfoData(val)
        },
        //多选后删除操作
        handleSelectionChange: function(val){
            val.forEach(item=> {
                this.deleteid.push(item.id)
            })
            console.log(val);
        },
        //避免分页时选中数据重新请求后台
        getRowKey(row){
            return row.id
        },
    }
}
</script>

<style lang="scss" scoped>
    .container {
        font-size: 16px;
        padding-left: 20px;
        .header{
            padding-left: 20px;
            .el-row {
                margin: 40px 0;
                .el-col{
                    display: flex;
                    line-height: 40px;
                    p {
                        word-break:keep-all;
                        text-align: center;
                    }
                }
            }
        }
        .category-container {
            margin-right: 20px;
            margin-top: 20px;
        }
        .button-new-tag {
                margin-left: 10px;
                margin-top: 20px;
                height: 32px;
                line-height: 30px;
                padding-top: 0;
                padding-bottom: 0;
            }
            .input-new-tag {
                width: 90px;
                margin-left: 10px;
                vertical-align: bottom;
            }
    }
</style>


