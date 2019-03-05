<template>
    <div class="container">
        <div class="queryInfo">
            <el-row :gutter="40">
                <el-col :span="8"><p>考生姓名：</p><el-input v-model="name" class="" placeholder="考生姓名" clearable></el-input></el-col>
                <el-col :span="8"><p>证件号码：</p>
                    <el-input v-model="idNumber" class="" placeholder="证件号码" clearable></el-input>
                </el-col>
                <el-col :span="8"><p>报考专业：</p>
                    <el-select v-model="professional" placeholder="全部">
                        <el-option v-for="item in professionalOptions" :key="item.value" :label="item.label" :value="item.value"> </el-option>
                    </el-select>
                </el-col>
            </el-row>
            <el-row :gutter="40">
                <el-col :span="8" :offset="0"><p>报考级别：</p>
                    <el-select v-model="level" placeholder="全部">
                        <el-option v-for="item in levelOptions" :key="item.value" :label="item.label" :value="item.value"> </el-option>
                    </el-select>
                </el-col>
                <el-col :span="68"><p>负责报名机构：</p>
                    <el-input v-model="organization" class="" placeholder="报名机构" clearable></el-input>
                </el-col>
                <el-col :span="8">
                    <p>负责报名老师：</p>
                    <el-input v-model="teacherName" class="" placeholder="老师姓名" clearable></el-input>
                </el-col>
            </el-row>
            <el-row :gutter="40">
                <el-col :span="4" :offset="20">
                    <el-button type="primary" @click.prevent="queryInfo">筛选</el-button>
                </el-col>
            </el-row>
        </div>
        <el-container class="queryResult">
            <el-header>
                <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="currentPage" :page-sizes="[50, 100, 150, 200]" background :page-size="pageSize" layout="total, sizes, prev, pager, next, jumper" :total="totalNumber">
                </el-pagination>
            </el-header>
            <el-main>
                <table-mixin>
                    <el-table  v-loading="queryData.loading" :data="queryData.body" border :default-sort="{prop: 'date', order: 'descending'}">
                        <el-table-column type="selection" align="center"> </el-table-column>
                        <el-table-column v-for="(item,index) in head" :prop="item.key" :label="item.name" :key="index" align="center"></el-table-column>
                    </el-table>
                </table-mixin>
            </el-main>
        </el-container>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            professional: [],
            professionalOptions: [{value: 'all',label: '全部'},{value: 'folkOrchestra',label: '民族管弦乐器'},{value: 'clavier',label: '键盘乐器'},{value: 'vocal',label: '声乐'},{value: 'westernOrchestra',label: '西洋管弦乐器'},{value: 'basicMusic',label: '基本乐科'},{value: 'recite',label: '朗诵'}],
            level: '',
            levelOptions: [{value: 'all',label: '全部'},{value: 'first',label: '一级'},{value: 'second',label: '二级'},{value: 'third',label: '三级'},{value: 'fourth',label: '四级'},{value: 'fifth',label: '五级'},{value: 'sixth',label: '六级'},{value: 'seventh',label: '七级'},{value: 'eighth',label: '八级'},{value: 'ninth',label: '九级'},{value: 'tenth',label: '十级'},{value: 'most',label: '表演文凭级'}],
            idNumber: '',
            organization: '',
            teacherName: '',
            currentPage: 1,
            totalNumber: 400,
            pageSize: 50,
            queryData: {
                body: []
             },
            head: [
                {key: 'studentName',name: '姓名'},{key: 'professional',name: '证件号码'},{key: 'level',name: '报考专业'},{key: 'signTime',name: '报考级别'},
                {key: 'signNo',name: '负责报名机构'},{key: 'idCardType',name: '负责报名老师'}]
        }
        
    },
    mounted(){
        this.queryInfo()
    },
    methods: {
       handleSizeChange(val) {
        console.log(`每页 ${val} 条`);
      },
      handleCurrentChange(val) {
        console.log(`当前页: ${val}`);
      },
      //查询考生
      queryInfo(){
          
      }
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