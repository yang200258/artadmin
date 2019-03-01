<template>
    <div class="richtext">
        <el-row>
            <el-col :span="16">
                <span style="color: red">*</span><span>内容：</span>
                <quill-editor class="richedit" :value="quillContent" :options="editorOption"  style="height: 400px" @change="editContent($event)"></quill-editor>
            </el-col>
        </el-row>
    </div>
</template>

<script>
import { quillEditor } from "vue-quill-editor"; //调用编辑器
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';
import 'quill/dist/quill.bubble.css';
export default {
    data(){
        return{
            editorOption: {
                modules: {
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
                        ['link', 'image', 'video']
                    ]
                }
            },
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
    methods: {
        editContent: function($event){
            console.log('event',$event);
            this.$store.commit('publishinfo/setquillContent',$event.html)
            // console.log(this.$store.state.publishinfo.quillContent);
            // this.$emit('editContent',content)
        }
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


