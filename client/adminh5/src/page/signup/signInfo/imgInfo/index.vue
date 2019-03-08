<template>
    <div class="img-container">
        <div class="option">
            <el-button>下载</el-button>
            <el-button @click="back">返回</el-button>
        </div>
        <div class="imginfo">
            <el-carousel :interval="5000" arrow="always" class="img" autoplay="false">
                <el-carousel-item v-for="item in imgurl" :key="item">
                    <img :src="item" alt="">
                </el-carousel-item>
            </el-carousel>
        </div>
    </div>
</template>


<script>
export default {
    data(){
        return {
            imgurl: []
        }
    },
    mounted(){
        if(this.$route.params.imgurl) {
            this.imgurl = this.$route.params.imgurl
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
            width: 867px;
            height: 1298px;
            border: 1px solid rgb(3, 3, 3);
            .el-carousel__container {
                height: 100%;
            }
            .img {
                width: 100%;
                height: 100%;
            }
        }
    }
</style>


