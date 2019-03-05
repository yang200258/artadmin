<template>
    <div class="publish-inform">
        <el-row>
            <span style="color:red">*</span><span>通知类型：</span>
            <el-select v-model="type" placeholder="请选择通知类型" @change="changeSelect">
                <el-option v-for="item in informTypeOption" :key="item.value" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-row>
        <el-row>
            <span style="color:red">*</span><span>通知对象：</span>
            <span @click.prevent="goInform" class="clickStyle" style="">点此查看</span>
        </el-row>
        <richtext class="richtext"></richtext>
        <el-row class="footer">
            <el-col :offset="8">
                <el-button type="primary" @click="publishInform">发布</el-button>  
                <el-button type="primary" @click="cancel">取消</el-button>
            </el-col>
        </el-row>
    </div>
</template>
<script>
import richtext from '../../common/richtext'
export default {
    data(){
        return{
            // type: '',
            informTypeOption: [{value: '1',label: '成绩查询'},{value: '2',label: '准考证领取'},{value: '3',label: '考场查询'},
                            {value: '4',label: '考试报名'},{value: '5',label: '大赛通知'},{value: '6',label: '定向通知'}],
        }
    },
    components: {
        richtext
    },
    mounted(){
        this.$store.commit('publishinfo/setquillContent','')
    },
    computed: {
        uid_arr(){
            return this.$store.state.informobject.addinformobjectdata.uid_arr
        },
        type(){
            return this.$store.state.informobject.type
        },
        content(){
            return this.$store.state.publishinfo.quillContent
        }
    },
    methods: {
        //点击查看通知对象
        goInform: function(){
            this.$router.push({
                name: 'addinformObject',
            })
        },
        //发布通知
        publishInform: function(){
            const {type,uid_arr,content} = this.type
            this.$axios({
                url: '/inform/add',
                method: 'post',
                data: {type,content,uid_arr}
            }).then(res=>{
                console.log('发布通知响应',res);
                if(res && !res.error) {
                    this.$store.commit('informobject/setType','')
                    this.$store.commit('publishinfo/setquillContent','')
                    this.$store.commit('informobject/setaddInformobject',{})
                    this.$router.push({
                        name: 'informlist'
                    })
                } else {
                    alert(res.msg)
                }
            }).catch(err=>{
                console.log(err);
            })
        },
        //取消发布
        cancel: function(){
            this.$router.go(-1)
        },
        //改变类型事件
        changeSelect: function(){
            this.$store.commit('informobject/setType',this.type)
        }

    },
}
</script>


<style lang="scss" scoped>
    .publish-inform {
        font-size: 16px;
        margin-left: 100px;
        margin-top: 100px;
        .richtext{
            margin-left: 0;
        }
        .el-row{
            margin: 40px 0;
            .clickStyle {
                color:#aaa;
                text-decoration:underline;
                &:hover {
                    cursor: pointer;
                }
            }
        }
        .footer {
            margin: 80px 0;
        }
    }
</style>


