<template>
    <div class="img-container">
        <div class="option">
            <el-button @click="download(imgurl)">下载</el-button>
            <el-button @click="back">返回</el-button>
        </div>
        <div class="imginfo">
            <el-carousel class="img" :autoplay="false" :interval="10000000" indicator-position="outside">
                <el-carousel-item v-for="item in imgurl" :key="item" style="height:1298px;" >
                    <img :src="item" alt="" v-if="item !== ''">
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
        },
        download(){
            let url = document.getElementsByClassName('is-active')[1].children[0].src
            console.log(url);
            // let url = `https://www.hnyskj.net/adminapi/download/zp?token=${token}&exam_site_id=${exam_site_id}`
            let link = document.createElement('a')
            link.style.display = 'none'
            link.href = url
            link.target = '_Blank'
            document.body.appendChild(link)
            link.click()
        }
    }
}
</script>


<style lang="scss" scoped>
.el-carousel__container {
    height: 100%!important;
}
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
            .el-carousel {
                .el-carousel__container {
                    height: 100%!important;
                }
            }
            .img {
                width: 100%;
                height: 100%;
                .el-carousel__container {
                    width: 100%;
                    height: 100%!important;
                }
            }
        }
    }
</style>


