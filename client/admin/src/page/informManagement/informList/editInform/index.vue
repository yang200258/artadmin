<template>
    <div class="edit-inform">
        <el-row>
            <span style="color:red">*</span><span>通知类型：</span>
            <el-select :value="filters[type]" placeholder="请选择通知类型" @change="changeType">
                <el-option v-for="item in informTypeOption" :key="item.value" :label="item.label" :value="item.value"></el-option>
            </el-select>
        </el-row>
        <el-row>
            <span style="color:red">*</span><span>通知对象：</span>
            <span @click.prevent="goInform" class="clickStyle" style="">点此查看</span>
        </el-row>
        <richtext></richtext>
        <el-row class="footer">
            <el-col :offset="8">
                <el-button type="back" @click="back">返回</el-button>  
                <el-button type="save" @click="save">保存</el-button>
            </el-col>
        </el-row>
    </div>
</template>
<script>
import richtext from '@/page/common/richtext'
export default {
    data(){
        return{
            // type: '',
            informTypeOption: [{value: '1',label: '成绩查询'},{value: '2',label: '准考证领取'},{value: '3',label: '考场查询'},
                        {value: '4',label: '考试报名'},{value: '5',label: '大赛通知'},{value: '6',label: '定向通知'}],
            uid_arr: [],
        }
    },
    components: {
        richtext
    },
    mounted(){ 
        this.getInform()
        if(this.$route.params.uid_arr && this.$route.params.uid_arr.length) {
            this.uid_arr = this.$route.params.uid_arr
        }
    },
    methods: {
        //返回
        back: function(){
            this.$router.push({
                name: 'informlist'
            })
        },
        changeType: function(val){
            this.$store.commit('informobject/setType',val)
        },
        //获取页面跳转后通知数据
        getInform: function(){
            const inform_id = this.$route.params.inform_id
            this.$axios({
                url: '/inform/object/list',
                method: 'post',
                data: {inform_id}
            }).then(res=> {
                console.log('获取单条通知数据',res);
                if(res && !res.error) {
                    this.uid_arr = []
                    if(this.$route.params.firstEdit) {
                        this.$store.commit('informobject/seteditInformobject',res.data.list)
                    }
                    res.data.list.forEach(item=> {
                        this.uid_arr.push(item.uid)
                    })
                }
            })
        },
        //完成编辑通知
        save: function(){
            const {inform_id,uid_arr} = this.$route.params
            const type = this.$store.state.informobject.type
            const content = this.$store.state.publishinfo.quillContent
            this.$axios({
                url: '/inform/edit',
                method: 'post',
                data: {type,content,id: inform_id,uid_arr}
            }).then(res=> {
                console.log('编辑通知响应',res);
                if(res && !res.error) {
                    this.$store.commit('informobject/setType',0)
                    this.$store.commit('publishinfo/setquillContent','')
                    this.$store.commit('informobject/seteditInformobject',[])
                    this.$router.push({
                        name: 'informlist'
                    })
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        //点击查看通知对象
        goInform: function(){
            const inform_id = this.$route.params.inform_id || ''
            const uid_arr = this.$route.params.uid_arr || this.uid_arr ||  []
            console.log('uid_arr',uid_arr);
            if(inform_id && uid_arr && uid_arr.length ) {
                this.$router.push({
                    name: 'editinformObject',
                    params: {
                        inform_id,
                        uid_arr,
                    }
                })
            } else {
                alert('通知编号或通知对象不存在，请检查！')
            }
            
        },

    },
    computed: {
        type(){
            return this.$store.state.informobject.type
        },
        filters(){
            return this.$store.state.informobject.filter
        },

    }
}
</script>


<style lang="scss" scoped>
    .edit-inform {
        font-size: 16px;
        margin-left: 100px;
        margin-top: 100px;
        .el-row{
            margin: 40px 0;
            &:first-child,&:nth-child(2) {
                margin-left: 100px;
            }
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


