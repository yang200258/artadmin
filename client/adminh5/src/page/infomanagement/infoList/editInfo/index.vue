<template>
    <div class="container" v-if="compelete">
        <info></info>
        <richtext class="richtext"></richtext>
        <el-row class="footer"  v-if="status !== '草稿'">
            <el-col :offset="8">
                <el-button @click="back"  style="width:6%;margin-right:50px;">返回</el-button>  
                <el-button @click="saveEdit" style="width:6%;" type="primary">保存修改</el-button>
            </el-col>
        </el-row>
        <el-row class="footer">
            <el-col :offset="8" v-if="status == '草稿'">
                <el-button @click="saveExample" style="width:6%;margin-right:50px;">存草稿</el-button>
                <el-button @click="saveEdit" style="width:6%;" type="primary">发布</el-button>  
            </el-col>
        </el-row>
    </div>
</template>

<script>

import info from '../../common/info'
import richtext from '@/page/common/richtext'
import {mapMutations,mapState} from 'vuex'
export default {
    data(){
        return{
            compelete: false,
            status : ''
        }
    },
    mounted(){
        if(this.$route.params.status) this.status = this.$route.params.status
        console.log('this.status',this.status);
        this.cleardata()
        this.getInfo()
    },
    components: {
        info,
        richtext
    },
    computed: {
        ...mapState('publishinfo',{
            publishData: state=> state.publishData,
        })
    },
    methods: {
        //获取信息详情
        getInfo: function(){
            console.log(this.$route.params.id)
            this.$axios({
                url: '/msg/detail',
                method: 'post',
                data: {
                    id: this.$route.params.id
                }
            }).then(res=> {
                console.log('信息详情数据',res);
                if(res && !res.error){
                    this.$store.commit('publishinfo/setPublishData',res.data)
                    this.$store.commit('publishinfo/setquillContent',res.data.content)
                    this.compelete = true
                } else {
                    this.$message.warning('无法获取该信息详情，请重试！')
                    this.compelete = true
                }
            }).catch(err=>{
                console.log(err);
            })
        },
        //获取store清除数据方法
        ...mapMutations({
            cleardata: 'publishinfo/cleardata'
        }),
        //返回
        back:function(){
            this.$router.go(-1)
        },
        //保存修改
        saveEdit:function(){
            const publishData = this.$store.state.publishinfo.publishData
            const quillContent = this.$store.state.publishinfo.quillContent
            // publishData.status = this.$store.state.publishinfo.publishData.status
            publishData.content = quillContent
            publishData.id = this.$route.params.id
            this.$axios({
                method: 'post',
                url: '/msg/edit',
                data: publishData
            }).then(res=> {
                console.log('保存信息响应',res);
                if(res && !res.error) {
                    this.$message.success(res.msg)
                    this.$router.push({
                        name: 'infoList'
                    })
                } else {
                    this.$message.warning(res.msg)
                }
            }).catch(err=>{
                console.log(err);
            })
        },
        //存草稿
        saveExample: function(){
            this.addData(2)
        },
        //发布信息或存草稿公用方法
        addData: function(status){
            const publishData = this.$store.state.publishinfo.publishData
            const quillContent = this.$store.state.publishinfo.quillContent
            publishData.status = status
            publishData.content = quillContent
            this.$axios({
                method: 'post',
                url: '/msg/add',
                data: publishData
            }).then(res=> {
                console.log('添加数据响应',res);
                if(res && !res.error) {
                    this.$store.commit('publishinfo/setquillContent','')
                    this.cleardata()
                    this.$router.push({
                        name: 'infoList'
                    })
                }
            }).catch(err=>{
                console.log(err);
            })
        },
    }
}
</script>


<style lang="scss" scoped>
    .container {
        .footer {
            margin: 60px 0;
        }
    }
</style>


