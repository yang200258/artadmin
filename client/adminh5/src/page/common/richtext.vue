<template>
    <div class="richtext">
        <el-row>
            <el-col :span="16">
                <span style="color: red">*</span><span>内容：</span>
                <input type='file' @change="update" ref='contentUpload' id='avatar-uploader'/>
                <quill-editor class="richedit" :value="quillContent" ref="myQuillEditor" :options="editorOption"  style="height: 400px" @change="editContent($event)"></quill-editor>
            </el-col>
        </el-row>
    </div>
</template>

<script>
import { quillEditor,Quill  } from "vue-quill-editor"; //调用编辑器
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
Quill.register('modules/ImageExtend', ImageExtend)
export default {
    data(){
        return{
            
        }
    },
    created(){
        this.actionUrl = '/upload';
        this.editorOption =  {
                modules: {
                    ImageExtend: {  // 如果不作设置，即{}  则依然开启复制粘贴功能且以base64插入 
                        name: 'img',  // 图片参数名
                        size: 1,  // 可选参数 图片大小，单位为M，1M = 1024kb
                        action: this.actionUrl,  // 服务器地址, 如果action为空，则采用base64插入图片
                        // response 为一个函数用来获取服务器返回的具体图片地址
                        // 例如服务器返回{code: 200; data:{ url: 'baidu.com'}}
                        // 则 return res.data.url
                        response: (res) => {
                            return res.data
                        },
                        headers: (xhr) => {
                        // xhr.setRequestHeader('myHeader','myValue')
                        },  // 可选参数 设置请求头部
                    },
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
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
                        ['link', 'image', 'video'],
                        'image': function (value) {  //劫持quill自身的文件上传，用原生替换
                            if (value) {
                                    document.querySelector('#avatar-uploader').click()
                                } else {
                                    this.quill.format('image', false);
                                }
                            }
                    ]
                }
            },
    },
    components: {
        quillEditor
    },
    computed: {
        quillContent(){
            return this.$store.state.publishinfo.quillContent
        }
    },
    methods: {
        editContent: function($event){
            this.$store.commit('publishinfo/setquillContent',$event.html)
            // console.log(this.$store.state.publishinfo.quillContent);
            // this.$emit('editContent',content)
        },
        update(){
            console.log('进入图片拦截模式');
            let inputDOM = this.$refs.contentUpload;
			this.file = inputDOM.files[0];
			var formdata = new FormData();
			formdata.append('file',document.getElementById("avatar-uploader").files[0]);
			this.$axios({
				url: this.actionUrl,
				method: 'post',
				data: formdata,
			}).then((res)=>{
                if(res && !res.error) {
                    this.imgUrl = res.data.url[0]
                }
				let quill = this.$refs.myQuillEditor.quill;
				const range = quill.getSelection();
				if(range){
					quill.insertEmbed(range.index, 'image',this.imgUrl); 
				}  
				quill.setSelection(quill.getSelection().index+1)
			}).catch((err)=>{
				console.log(err)
			})
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


