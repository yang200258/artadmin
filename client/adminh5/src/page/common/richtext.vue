<template>
    <div class="richtext">
        <el-row>
            <el-col :span="16">
                <span style="color: red">*</span><span>内容：</span>
                <el-upload
                    class="avatar-uploader"
                    action="https://www.hnyskj.net/adminapi/upload"
                    name="img"
                    :data="uploaddata"
                    :show-file-list="false"
                    :on-success="uploadSuccess"
                    :on-error="uploadError"
                    :before-upload="beforeUpload">
                </el-upload>
                <el-row v-loading="quillUpdateImg">
                    <quill-editor class="richedit" :value="quillContent" ref="myQuillEditor" :options="editorOption"  style="height: 400px" @change="editContent($event)" 
                @ready="onEditorReady($event)" ></quill-editor>
                </el-row>
                
            </el-col>
        </el-row>
    </div>
</template>

<script>
import { quillEditor} from "vue-quill-editor"; //调用编辑器
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
import Auth from '@/util/auth'
export default {
    data(){
        return{
            quillUpdateImg: false,
            editorOption:  {
                placeholder: '',
                theme: 'bubble',
                modules: {
                    toolbar: {
                        container: [['bold', 'italic', 'underline', 'strike'],
                                        ['blockquote', 'code-block'],
                                        [{ 'header': 1 }, { 'header': 2 }],
                                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                        [{ 'script': 'sub' }, { 'script': 'super' }],
                                        [{ 'indent': '-1' }, { 'indent': '+1' }],
                                        [{ 'direction': 'rtl' }],
                                        [{ 'size': ['small', false, 'large', 'huge'] }],
                                        // [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                                        // [{ 'font': [] }],
                                        [{ 'color': [] }, { 'background': [] }],
                                        [{ 'align': [] }],
                                        ['clean'],
                                        ['link', 'image', 'video']],
                        handlers:{
                            'image': function (value) {  //劫持quill自身的文件上传，用原生替换
                            if (value) {
                                    document.querySelector('.avatar-uploader input').click()
                                } else {
                                    this.quill.format('image', false);
                                }
                            }
                        }
                    }
                },
            }
        }
    },
        
    components: {
        quillEditor
    },
    computed: {
        quillContent(){
            return this.$store.state.publishinfo.quillContent
        }
    },
    mounted(){
        this.uploaddata = {token: Auth.hasToken()}
    },
    methods: {
        beforeUpload() {
            // 显示loading动画
            this.quillUpdateImg = true
        },
        uploadSuccess(res) {
            // res为图片服务器返回的数据
            // 获取富文本组件实例
            let quill = this.$refs.myQuillEditor.quill
            // 如果上传成功
            if (res && !res.error) {
                // 获取光标所在位置
                let length = quill.getSelection().index;
                // 插入图片  res.info为服务器返回的图片地址
                quill.insertEmbed(length, 'image', res.data.url[0])
                // 调整光标到最后
                quill.setSelection(length + 1)
            } else {
                alert('图片插入失败')
            }
            // loading动画消失
            this.quillUpdateImg = false
        },
        // 富文本图片上传失败
        uploadError() {
            // loading动画消失
            this.quillUpdateImg = false
            this.alert('图片插入失败')
        },
        editContent: function($event){
            this.$store.commit('publishinfo/setquillContent',$event.html)
        },
    }
}
</script>

<style lang="scss" scoped>
    .richtext {
        // margin: 0;
        margin-top: 60px;
        margin-left: 100px;
        padding: 0;
        .el-col {
            .richedit {
                margin-top: 20px;
            }
        }
    }
</style>


