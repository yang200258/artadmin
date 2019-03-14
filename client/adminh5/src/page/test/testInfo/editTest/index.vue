<template>
    <div class="container" >
        <div v-loading="loadingTable" element-loading-text="拼命加载中" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.5)">
            <test-info></test-info>
            <test-location></test-location>
            <div class="button">
                <el-button @click="back">返回</el-button>
                <el-button @click="save">保存修改</el-button>
            </div>
        </div>
    </div>
</template>

<script>
import testInfo from '@/components/test/testInfo.vue'
import testLocation from '@/components/test/testLocation.vue'
import {mapMutations,mapState} from 'vuex'
import util from '@/util/util'
export default {
    data() {
        return {
            loading: false,
            sites: []
        }
    },
    mounted(){
        if(this.$route.params.id) {
            this.getDetail(this.$route.params.id)
        }
    },
    components: {
        testInfo,
        testLocation
    },
    computed: {
        ...mapState('test',{
            examSite: state=> state.examSite,
            baseinfo: state=> state.baseinfo,
            isEdit: state=> state.isEdit,
        }),
    },
    methods: {
        ...mapMutations({
            setBaseinfo: 'test/setBaseinfo',
            setExamSite: 'test/setExamSite'
        }),
        //获取考试详情数据
        getDetail: function(id){
            this.loading = true
            this.$axios({
                url: '/exam/detail',
                method: 'post',
                data: {id}
            }).then(res=> {
                if(res && !res.error) {
                    let {name,number,apply_time_start,apply_time_end,exam_time_start,exam_time_end,create_at} = res.data.exam
                    let applyTime = [apply_time_start,apply_time_end]
                    let examTime = [exam_time_start,exam_time_end]
                    this.setBaseinfo({name,number,applyTime,examTime,create_at})
                    let examSite = res.data.exam_site
                    this.sites = examSite
                    util.mergeJson(examSite).then(arr=> {
                        console.log('数据处理成功！',arr)
                        util.turn(arr).then(t=>{
                            console.log('数据处理成功！',t);
                            util.turnFinal(t).then(final=> {
                                console.log('数据处理成功！',final);
                                this.setExamSite(final)
                            })
                        })
                    })
                } else {
                    this.$message.warning(res.msg)
                }
                this.loading = false
            }).catch(err=> {
                console.log(err);
                this.loading = false
            })
        },
        //返回修改
        back: function(){
            this.$store.commit('test/setEdit',false)
            this.$store.commit('test/initExamSite')
            this.$store.commit('test/initBaseinfo')
            this.$router.go(-1)
        },
        //保存编辑
        save: function(){
            const id = this.$route.params.id
            console.log(this.baseinfo);
            console.log(this.examSite);
            const {name,number,examTime,applyTime} = this.baseinfo
            const apply_time_start = util.filterDateTime(applyTime[0])
            const apply_time_end = util.filterDateTime(applyTime[1])
            const exam_time_start = util.filterDateTime(examTime[0])
            const exam_time_end = util.filterDateTime(examTime[1])
            const exam_site = []
            this.examSite.forEach(item=> {
                if(item.time1.value) {
                    exam_site.push({
                        address: item.address,
                        room: '考场1',
                        time: util.filterDateTime(item.time1.value),
                        id: item.time1.id || ''
                    })
                }
                if(item.time && item.time.length) {
                    item.time.forEach(t=> {
                        exam_site.push({
                            address: item.address,
                            room: '考场1',
                            time: util.filterDateTime(t.value),
                            id: t.id || ''
                        })
                    })
                }
                if(item.rooms && item.rooms.length) {
                    item.rooms.forEach((room,i)=> {
                        exam_site.push({
                            address: item.address,
                            room: '考场' + (i+2),
                            time: util.filterDateTime(room.time1.value),
                            id: room.time1.id || ''
                        })
                        if(room.times && room.times.length) {
                            room.times.forEach(r=> {
                                exam_site.push({
                                    address: item.address,
                                    room: '考场' + (i+2),
                                    time: util.filterDateTime(r.value),
                                    id: r.id || ''
                                })
                            })
                        }
                    })
                }
            })
            this.$axios({
                url: '/exam/edit',
                method: 'post',
                data: {id,name,number,apply_time_start,apply_time_end,exam_time_start,exam_time_end,exam_site}
            }).then(res=> {
                if(res && !res.error) {
                    this.$message.success('编辑成功！')
                    this.$store.commit('test/initExamSite')
                    this.$store.commit('test/initBaseinfo')
                    this.$router.push({
                        name: 'testInfo'
                    })
                } else {
                    this.$message.warning(res.msg)
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        
    },
    beforeDestroy(){
        this.$store.commit('test/initExamSite')
        this.$store.commit('test/initBaseinfo')
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


