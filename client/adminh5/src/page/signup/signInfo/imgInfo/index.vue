<template>
    <div class="img-container">
        <div class="option">
            <el-button>下载</el-button>
            <el-button @click="back">返回</el-button>
        </div>
        <div class="imginfo">
            <img :src="imgsrc" alt="">
        </div>
    </div>
</template>


<script>
export default {
    data(){
        return {
            imgsrc: ''
        }
    },
    methods: {
        back(){
            this.$router.go(-1)
        }
    }
}
</script>


<style lang="scss" scoped>
    .img-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 50px;
        .option {
            .el-button {
                margin-right: 60px;
            }
        }
        .imginfo {
            margin: 30px 0;
            width: 600px;
            height: 700px;
            border: 1px solid rgb(3, 3, 3)
        }
    }
</style>


