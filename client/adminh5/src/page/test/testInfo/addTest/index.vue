<template>
    <div class="container">
        <test-info></test-info>
        <test-location></test-location>
        <div class="button">
            <el-col style="width:40%;" type="primary"><el-button @click="save" :disabled="status">完成</el-button></el-col>
        </div>
    </div>
</template>

<script>
import testInfo from '../../common/testInfo.vue'
import testLocation from '../../common/testLocation.vue'
import {mapState} from 'vuex'
import util from '@/util/util'
export default {
    
    data(){
        return {
            // examSite: [{address: "",time1: '',time: [],rooms: []}]
        }
    },
    components: {
        testInfo,
        testLocation
    },
    mounted() {
        this.$store.commit('test/initExamSite')
        this.$store.commit('test/initBaseinfo')
    },
    computed: {
        ...mapState('test',{
            examSite: state=> state.examSite,
            baseinfo: state=> state.baseinfo,
        }),
        status(){
            return (this.examSite[0].address && this.examSite[0].time1 && this.baseinfo.name&& this.baseinfo.examTime&& this.baseinfo.number&& this.baseinfo.applyTime) ? false : true
        }
    },
    methods: {
        save: function() {
            console.log(this.examSite);
            console.log(this.baseinfo);
            const {name,number,examTime,applyTime} = this.baseinfo
            const apply_time_start = util.filterDateTime(applyTime[0])
            const apply_time_end = util.filterDateTime(applyTime[1])
            const exam_time_start = util.filterDateTime(examTime[0])
            const exam_time_end = util.filterDateTime(examTime[1])
            const exam_site = []
            this.examSite.forEach(item=> {
                if(item.address) {
                    if(item.time1) {
                        exam_site.push({
                            address: item.address,
                            room: '考场1',
                            time: util.filterDateTime(item.time1)
                        })
                    }
                    if(item.time && item.time.length) {
                        item.time.forEach(t=> {
                            exam_site.push({
                                address: item.address,
                                room: '考场1',
                                time: util.filterDateTime(t.value)
                            })
                        })
                    }
                    if(item.rooms && item.rooms.length) {
                        item.rooms.forEach((room,i)=> {
                            exam_site.push({
                                address: item.address,
                                room: '考场' + (i+2),
                                time: util.filterDateTime(room.time1)
                            })
                            if(room.times && room.times.length) {
                                room.times.forEach(r=> {
                                    exam_site.push({
                                        address: item.address,
                                        room: '考场' + (i+2),
                                        time: util.filterDateTime(r.value)
                                    })
                                })
                            }
                        })
                    }
                }
            })
            this.$axios({
                url: '/exam/add',
                method: 'post',
                data: {name,number,apply_time_start,apply_time_end,exam_time_start,exam_time_end,exam_site}
            }).then(res=> {
                if(res && !res.error) {
                    this.$message.success(res.msg)
                    this.$router.push({
                        name: 'testInfo'
                    })
                } else {
                    this.$message.warning(res.msg)
                }
            }).catch(err=> {
                console.log(err);
            })
        }
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
