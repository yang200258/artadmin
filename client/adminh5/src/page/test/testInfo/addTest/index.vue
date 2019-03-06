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
            // status: true
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
        }),
        status(){
            return (this.examSite.address && this.examSite.time1 && this.baseinfo.name&& this.baseinfo.examTime&& this.baseinfo.number&& this.baseinfo.applyTime) ? false : true
        }
    },
    methods: {
        save: function() {
            console.log(this.examSite);
            console.log(this.baseinfo);
            const {name,number,examTime,applyTime} = this.baseinfo
            const {address,rooms,sites,time,time1} = this.examSite
            console.log(applyTime,examTime);
            const apply_time_start = util.filterDateTime(applyTime[0])
            const apply_time_end = util.filterDateTime(applyTime[1])
            const exam_time_start = util.filterDateTime(examTime[0])
            const exam_time_end = util.filterDateTime(examTime[1])
            const exam_site = []
            if(time1) {
                exam_site.push({
                    address: address,
                    room: '考场1',
                    time: util.filterDateTime(time1.value)
                })
            }
            if(time && time.length) {
                time.forEach(item=> {
                    exam_site.push({
                        address: address,
                        room: '考场1',
                        time: util.filterDateTime(item.value)
                    })
                })
            }
            if(rooms && rooms.length) {
                rooms.forEach((item,i)=> {
                    exam_site.push({
                        address: address,
                        room: '考场' + (i+2),
                        time: util.filterDateTime(item.time1)
                    })
                    if(item.times && item.times.length) {
                        item.times.forEach(time=> {
                            exam_site.push({
                                address: address,
                                room: '考场' + (i+2),
                                time: util.filterDateTime(time.value)
                            })
                        })
                    }
                })
            }
            if(sites && sites.length) {
                sites.forEach(site=> {
                    if(site.times && site.times.length) {
                        site.times.forEach(time=> {
                            exam_site.push({
                                address: site.site,
                                room: '考场1',
                                time: util.filterDateTime(time.value)
                            })
                        })
                    }
                })
            }
                this.$axios({
                    url: '/exam/add',
                    method: 'post',
                    data: {name,number,apply_time_start,apply_time_end,exam_time_start,exam_time_end,exam_site}
                }).then(res=> {
                    if(res && !res.error) {
                        this.$message.success(res.msg)
                        
                    } else {
                        this.$message.warn(res.msg)
                    }
                })
        }
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
