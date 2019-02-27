<template>
    <div class="container">
        <el-row class="infoType">
            <span style="color: red">*</span><span>信息分类：</span>
            <el-select v-model="publishData.cid" placeholder="请选择信息类型">
                <el-option v-for="item in infoTypeOptions" :key="item.id" :label="item.name" :value="item.id"></el-option>
            </el-select>
        </el-row>
        <el-row class="infoTitle">
            <el-col :span="1"><span style="color: red">*</span><span>标题：</span></el-col>
            <el-col :span="8"><el-input @input = "descInput" type="textarea" :autosize="{ minRows: 2, maxRows: 4}" placeholder="请输入内容" v-model="publishData.title"></el-input></el-col>
        </el-row>
        <el-row class="alnumber">
            <el-col :offset="8" :span="2"><div style="color: #bbb;">已填写<span style="color: red">{{gettitleNumber}}</span>个字</div></el-col>
        </el-row>
        <el-row class="infoImage">
            <span>封面图：</span>
            <el-upload
                name="file"
                :action="'api/upload'"
                :limit="1"
                accept=".jpg,.png" 
                list-type="picture-card"
                :data="uploaddata"
                :file-list="fileLists"
                :on-exceed="exceed"
                :on-preview="handlePictureCardPreview"
                :on-success="success"
                :on-remove="handleRemove">
                <i class="el-icon-plus"></i>
            </el-upload>
            <el-dialog :visible.sync="dialogVisible" :modal="false" title="查看大图片">
                <img width="100%" :src="dialogImageUrl" alt="">
            </el-dialog>
        </el-row>
        <el-row class="introduction">
            <el-col :span="1"><span style="color: red">*</span><span>引言：</span></el-col>
            <el-col :span="8"><el-input type="textarea" @input = "descInput2" :autosize="{ minRows: 2, maxRows: 4}" placeholder="请输入内容" v-model="publishData.intro"></el-input>
            </el-col>
        </el-row>
        <el-row class="alnumber">
            <el-col :offset="8" :span="2"><div style="color: #bbb;">已填写<span style="color: red">{{getintroductionNumber}}</span>个字</div></el-col>
        </el-row>
        <!-- <richtext class="richtext" :content="publishData.content"></richtext> -->
    </div>
</template>
<script>
// import richtext from '../../common/richtext'
import Auth from '@/util/auth'
import { mapGetters } from 'vuex';
export default {
    data(){
        return {
            infoTypeOptions: [],
            // titleNumber: 0,
            intro: '',
            // introductionNumber: 0,
            dialogImageUrl: '',
            dialogVisible: false,
            fileLists: [],
            uploaddata: {},
            cover_id: '',
        }
    },
    mounted(){
        //上传图片数据添加token
        this.uploaddata = {token: Auth.hasToken()}
        //获取信息分类列表
        this.getTypeList()
        //若跳转路由，展示图片
        if(this.$route.params.url) {
            this.fileLists = [{name: 'title',url: this.$route.params.url}]
        }
        
        
    },
    computed: {
        publishData() {
            return this.$store.state.publishinfo.publishData
        },
        ...mapGetters({
            gettitleNumber: 'publishinfo/gettitleNumber',
            getintroductionNumber: 'publishinfo/getintroductionNumber'
        }),
    },
    methods: {
        //输入标题时计算字数
        descInput: function(){

        },
        //输入引言时计算字数
        descInput2: function(){

        },
        
        handleRemove(file, fileList) {
            console.log(file, fileList);
        },
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            console.log(this.dialogImageUrl);
            this.dialogVisible = true;
        },
        //图片上传成功后修改cover——id
        success: function(response){
            console.log(response);
            if(response && !response.error) {
                this.publishData.cover_id = response.data.id[0]
            }
        },
        exceed: function() {
            alert('仅允许上传一张图片')
            return false
        },
        //获取信息分类列表
        getTypeList: function(){
            this.$axios({
                url: '/msg/category/list',
                method: 'post',
                data: {}
            }).then(res=>{
                console.log('获取到的信息分类列表',res);
                if(res && !res.error) {
                    this.infoTypeOptions = res.data
                }
            }).catch(err=> {
                console.log(err);
            })
        }
    }
}
</script>

<style lang="scss" scoped>
    .container {
        padding-left: 100px;
        font-size: 16px;
        .infoType {
            margin: 30px 0;
        }
        .alnumber {
            font-size: 14px;
        }
        .infoTitle {
            margin-bottom: 30px 0;
        }
        .infoImage {
            margin-bottom: 30px 0;
        }
        .introduction {
            margin-top: 30px;
        }
        
    }
</style>


