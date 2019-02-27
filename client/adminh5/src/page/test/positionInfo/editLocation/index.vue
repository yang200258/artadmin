<template>
    <div class="container">
        <div class="header">
            <el-row>
                <el-col :span="3" :push="1"><el-button type="primary">批量下载报名表</el-button></el-col>
                <el-col :span="3" :push="1"><el-button type="primary">批量下载准考证</el-button></el-col>
                <el-col :span="3" :push="1"><el-button type="primary">批量下载照片</el-button></el-col>
                <el-col :span="2" :push="8"><el-button type="primary" @click="sortExaminee = true">考生排序</el-button></el-col>
                <el-col :span="2" :push="8"><el-button type="primary" @click="addExaminee = true">添加考生</el-button></el-col>
            </el-row>
        </div>
         <!-- 考生排序弹出层 -->
        <el-dialog :before-close="closeSort" title="考生排序" width="30%" :visible="sortExaminee">
            <span>(按住拖动进行排序)</span>
                <draggable element="ul" v-model="sortData">
                    <transition-group>
                        <li v-for="(item,index) in sortData" :key="index">
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
                <add-examinee></add-examinee>
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
import addExaminee from '../../common/addExaminee'
export default {
    data(){
        return {
            head: [{key: 'number',name: '考生排位号'},{key: 'name',name: '考生姓名'},{key: 'testPosition',name: '证件号码'},{key: 'testDate',name: '报考专业'},
                    {key: 'testTime',name: '报考级别'},{key: 'testNumber',name: '负责报名机构'},{key: 'testNumber2',name: '负责报名老师'}],
            examineeData: [{number: '1561561',name: 'revr',testPosition: '1566516516516',testDate: '胡啊',testTime: '9',testNumber: 'v发VS绿萼',testNumber2: '要求去'},
                        {number: '1561561',name: 'revr',testPosition: '1566516516516',testDate: '胡啊',testTime: '9',testNumber: 'v发VS绿萼',testNumber2: '要求去'}],
            isLoading: false,
            sortExaminee: false,
            addExaminee: false,
            deleteExaminee: false,
            sortData: [{name:'胡啊',index: '1'},{name:'fede',index: '2'}]
        }
    },
    components: {
        draggable,
        addExaminee
    },
    mounted(){
        // this.getExamineeInfo()
    },
    methods: {
        deleteInfo(){
            
        },
        getExamineeInfo(){
            this.isLoading = true
            const id = this.$route.params.gid
            this.$axios({
                url : '/test/examineeedit',
                type: 'post',
                data: {id}
            }).then(res=> {
                console.log('查询到的该考场考生信息',res);
                if(res && !res.error) {
                    this.examineeData = res.data.list
                } else {
                    this.$message.warn(res.msg)
                }
                this.isLoading = false
            }).catch(err=> {
                console.log(err);
                this.$message.warn(err)
            })
        },
        //子组件中传入方法
        saveSort: function(){
            
        },
        closeSort: function(){
            this.sortExaminee = false
        },
        closeSave: function(){
            this.sortExaminee = false
        },
        closeDelete: function(){
            this.deleteExaminee = false
        },
        cancelSort: function(){
           this.sortExaminee = false
        },
        cancelSave: function(){
           this.addExaminee = false
        },
        cancelDelete: function(){
           this.deleteExaminee = false
        },
        saveAddExaminee: function(){

        }
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
    }
</style>
