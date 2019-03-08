<template>
    <div class="img-container">
        <div class="option">
            <el-button>下载</el-button>
            <el-button @click="back">返回</el-button>
        </div>
        <div class="imginfo">
            <el-carousel :interval="5000" arrow="always" class="img">
                <img :src="bm_continuous_image_url" alt="">
                <img :src="bm_image_url" alt="">
                <img :src="bm_image_url" alt="">
            </el-carousel>
        </div>
    </div>
</template>


<script>
export default {
    data(){
        return {
            bm_continuous_image_url: '',
            bm_image_url: '',
            kz_image_url: ''
        }
    },
    mounted(){
        if(this.$route.params.bm_continuous_image_url) {
            this.bm_continuous_image_url = this.$route.params.bm_continuous_image_url
        }
        if(this.$route.params.bm_image_url) {
            this.bm_image_url = this.$route.params.bm_image_url
        }
        if(this.$route.params.kz_image_url) {
            this.kz_image_url = this.$route.params.kz_image_url
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
            width: 40%;
            height: 80%;
            border: 1px solid rgb(3, 3, 3);
            .img {
                width: 100%;
                height: 100%;
            }
        }
    }
</style>


