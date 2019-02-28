<template>
    <div class="container">
        <test-info></test-info>
        <test-location></test-location>
        <div class="button">
            <el-button>返回修改</el-button>
            <el-button>完成</el-button>
        </div>
    </div>
</template>

<script>
import testInfo from '../../common/testInfo.vue'
import testLocation from '../../common/testLocation.vue'
export default {
    data() {
        return {

        }
    },
    components: {
        testInfo,
        testLocation
    }
}
</script>

<style lang="scss" scoped>
    .container{
        .button{
            margin-top: 60px;
            margin-left: 35%;
            padding-bottom: 100px;
            .el-button {
                margin-left: 40px;
            }
        }
    }
</style>


