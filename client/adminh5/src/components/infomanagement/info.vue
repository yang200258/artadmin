<template>
    <div class="container">
        <el-form :model="publishData" :rules="rules">
            <el-form-item label="信息分类：" prop="cid">
                <el-select v-model="publishData.cid" placeholder="请选择信息类型">
                    <el-option v-for="item in infoTypeOptions" :key="item.id" :label="item.name" :value="item.id"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="标题：" prop="title">
                <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" placeholder="请输入标题" v-model="publishData.title"></el-input>
            </el-form-item>
            <el-row class="alnumber">
                <el-col :offset="8" :span="2"><div style="color: #bbb;">已填写<span style="color: red">{{gettitleNumber}}</span>个字</div></el-col>
            </el-row>
            <el-form-item label="封面图：">
                <el-upload
                class="avatar-uploader"
                :class="{disabled:uploadDisabled}"
                name="file"
                ref="upload"
                action="https://www.hnyskj.net/adminapi/upload"
                :limit = "parseInt('1')"
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
            </el-form-item>
            <el-form-item label="引言：" prop="intro">
               <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 4}" placeholder="请输入引言" v-model="publishData.intro"></el-input>
            </el-form-item>
            <el-row class="alnumber">
                <el-col :offset="8" :span="2"><div style="color: #bbb;">已填写<span style="color: red">{{getintroductionNumber}}</span>个字</div></el-col>
            </el-row>
        </el-form>
    </div>
</template>

<script>
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
            rules:{cid: [{required: true,message: '请选择信息类型',trigger: 'change'}],intro: [{required: true,message: '请输入内容',trigger: 'blur'}],
            title: [{required: true,message: '请输入内容',trigger: 'blur'}],},
            uploadDisabled: false
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
            this.uploadDisabled = true
            let upload_div = this.$refs.upload.$children[1].$el;
            upload_div.style.cssText = "display: none;"
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
        uploadDisabled:function() {
            return this.fileLists.length >0
        },
    },
    methods: {
        handleRemove(file, fileList) {
            this.uploadDisabled = false
            console.log(file, fileList);
            let upload_div = this.$refs.upload.$children[1].$el;
            upload_div.style.cssText = "display: inline-block;"
        },
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            console.log(this.dialogImageUrl);
            this.dialogVisible = true;
        },
        //图片上传成功后修改cover——id
        success: function(response, file, fileList){
            console.log(response, file, fileList);
            console.log(this.fileLists);
            console.log(this.fileLists.length);
            if(response && !response.error) {
                console.log('获取到图片上传后的回调',response);
                this.publishData.cover_id = response.data.id[0]
                this.uploadDisabled = true
                console.log(this.$refs.upload);
                let upload_div = this.$refs.upload.$children[1].$el;
                upload_div.style.cssText = "display: none;"
            }
        },
        exceed: function() {
            this.$message.warning('仅允许上传一张图片')
            return false
        },
        //获取信息分类列表
        getTypeList: function(){
            this.$axios({
                url: '/category/list',
                method: 'post',
                data: {}
            }).then(res=>{
                console.log('获取到的信息分类列表',res);
                if(res && !res.error) {
                    this.infoTypeOptions = res.data
                } else {
                    this.$message.warning(res.msg)
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
        padding-top: 50px;
        .el-textarea {
            display: block;
            width: 38%;
        }
        .infoType {
            margin: 30px 0;
        }
        .alnumber {
            font-size: 14px;
        }
        .infoTitle {
            margin-bottom: 30px 0;
            display: flex;

        }
        .infoImage {
            margin-bottom: 30px 0;
        }
        .introduction {
            margin-top: 30px;
            display: flex;
        }
        .disabled  {
            .el-upload--picture-card {
                background-color: #fbfdff;
                border: 1px dashed #c0ccda;
                border-radius: 6px;
                box-sizing: border-box;
                width: 148px;
                height: 148px;
                cursor: pointer;
                line-height: 146px;
                vertical-align: top;
                display: none!important;
            }
        }
        .disabled {
             .el-upload {
                 display: none!important;
             }
            
        }
    }
</style>


