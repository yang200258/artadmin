<template>
    <div class="container">
        <info class="info"></info> 
        <richtext class="richtext"></richtext>
        <el-row class="footer">
            <el-col :offset="8">
                <el-button @click="publish">发布</el-button>  
                <el-button @click="saveExample">存草稿</el-button>
            </el-col>
        </el-row>
    </div>
</template>

<script>
import info from "../common/info"
import richtext from '@/page/common/richtext'
import {mapMutations} from 'vuex'
export default {
    data() {
        return {
            status: 1
        }
    },
    components: {
        info,
        richtext
    },
    mounted(){
        this.cleardata()
    },
    methods: {
        //发布信息
        publish: function(){
            this.addData(1)
        },
        saveExample: function(){
            this.addData(2)
        },
        //获取store清除数据方法
        ...mapMutations({
            cleardata: 'publishinfo/cleardata'
        }),

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
                    this.$router.push({
                        name: 'infoList'
                    })
                }
            }).catch(err=>{
                console.log(err);
            })
        },
    },
}
</script>


<style lang="scss" scoped>
    .container {
        .footer {
            margin: 60px 0;
        }
    }
</style>

