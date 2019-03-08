<template>
    <div class="container" >
        <div v-loading="loadingTable" element-loading-text="拼命加载中" element-loading-spinner="el-icon-loading" element-loading-background="rgba(0, 0, 0, 0.8)">
            <test-info></test-info>
            <test-location></test-location>
            <div class="button">
                <el-button>返回修改</el-button>
                <el-button>完成</el-button>
            </div>
        </div>
    </div>
</template>

<script>
import testInfo from '../../common/testInfo.vue'
import testLocation from '../../common/testLocation.vue'
import {mapMutations} from 'vuex'
export default {
    data() {
        return {
            loading: false
        }
    },
    mounted(){
        this.getDetail(this.$route.params.id)
    },
    components: {
        testInfo,
        testLocation
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
                    this.mergeJson(examSite).then(res=> {
                        this.turn(res).then(arr=>{
                            this.turnFinal(arr).then(final=> {
                                console.log('数据处理成功！',final);
                                this.$store.commit('test/setExamSite',final)
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
        //将多项数组对象合并同类项
        mergeJson: function(arr){
            return new Promise((resolve)=> {
                const map = {}
                for(const site of arr) {
                    if(!map.hasOwnProperty(site.address)) {
                        map[site.address] = site
                        const val = map[site.address].exam_time
                        map[site.address].exam_time = [val]
                    } else {
                        map[site.address].exam_time.push(site.exam_time)
                    }
                }
                function transform(obj) {
                    let arr = []
                    for(let item in obj) {
                        arr.push(obj[item])
                    }
                    return arr
                }
                resolve(transform(map))
            })
            
        },
        //将合并后数组继续分类为相同考点
        turn: function(arr){
            return new Promise((resolve)=> {
                let o = {}
                arr.forEach(item=> {
                    let array = o[item['address']] || []
                    array.push(item)
                    o[item['address']] = array
                })
                resolve(o)
            })
        },
        //将处理后数据最终转换为所需
        turnFinal: function(o){
            return new Promise(resolve=> {
                let site = []
                for(let item in o) {
                    if(o[item].length == 1) {
                        if(o[item].exam_time.length == 1) {
                            site.push({address: o[item].address,time1: o[item].exam_time[0],time: [],rooms: [],key: Date.now()})
                        } else {
                            const time = []
                            o[item].exam_time.forEach((t,i)=> {
                                if(i>=1) {
                                    time.push({value:t,key: Date.now()})
                                }
                            })
                            site.push({address: o[item][0].address,time1: o[item].exam_time[0],time: time,rooms: [],key: Date.now()})
                        }
                    } else {
                            let time1 = ''
                            let time = []
                            let rooms = []
                            let times = []
                            // let address = 
                            o[item].forEach((l,i) => {
                            if(i == 0) {
                                time1 = l.exam_time[0]
                                if(l.exam_time.length == 1) {
                                    time = []
                                } else {
                                    l.exam_time.forEach((t,m)=> {
                                        if(m >=1) {
                                            time.push({value: t,key: Date.now()})
                                        }
                                    })
                                }
                            } else {
                                rooms.time1 = l.exam_time[0]
                                if(l.exam_time.length == 1) {
                                    rooms.times = []
                                    // rooms.push({time1:rooms_time1,key: Date.now() })
                                } else {
                                    l.exam_time.forEach((s,i)=> {
                                        if(i >=1) {
                                            times.push({value: s,key: Date.now()})
                                            
                                        }
                                    })
                                }
                                rooms.times = times
                            }
                        })
                        site.push({address: o[item][0].address,time1: time1,time: time,rooms: rooms,key: Date.now()})
                    }
                }
                console.log(site);
                resolve(site)
                
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


