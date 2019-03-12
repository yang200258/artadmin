<template>
    <el-container  v-if="isLoading">
        <el-container class="signinfo">
            <el-header>
                <el-row>
                    报名信息
                </el-row>
            </el-header>
            <el-main>
                <div class="baseinfo">
                    <el-row>
                        <el-col :span="24">
                            <span>考生照片：</span>
                            <img class="examinee-img" :src="detail.picture_url" alt="">
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">姓名：{{detail.name}}</el-col>
                        <el-col :span="8">拼音或英文：{{detail.pinyin}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">性别：{{detail.sex}}</el-col>
                        <el-col :span="8">国籍：{{detail.nationality}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">民族：{{detail.nation}}</el-col>
                        <el-col :span="8">证件类型：{{detail.id_type}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">证件号码：{{detail.id_number}}</el-col>
                        <el-col :span="8">联系电话：{{detail.phone}}</el-col>
                    </el-row>
                </div>
                <div class="line"></div>
                <div class="testinfo">
                    <el-row>
                        <el-col :span="8">考试名称：{{detail.exam.name}}</el-col>
                        <el-col :span="8">报考专业：{{detail.domain}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">报考级别：{{detail.level}}</el-col>
                        <el-col :span="8">考试曲目1：{{detail.track_one}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">考试曲目2：{{detail.track_two }}</el-col>
                        <el-col :span="8">考试曲目3：{{detail.track_three}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">考试曲目4：{{detail.track_four}}</el-col>
                        <el-col :span="8">考试曲目5：{{detail.track_five}}</el-col>
                    </el-row>
                    <el-row v-if="status.status !== '3'">
                        <el-col :span="8" ><el-button type="primary" plain v-if="detail.level  == '十级' || detail.level == '表演文凭级'" @click="getProfessional">查看考生专业证书</el-button></el-col>
                        <el-col :span="8" ><el-button type="primary" plain v-if="((detail.domain == '基本乐科' && detail.level  !== '一级') || (detail.domain !== '基本乐科' && (detail.level  !== '一级' ||detail.level  !== '二级')))  && detail.basic_certificate_url"  @click="getbase">查看考生基本乐科证书</el-button></el-col>
                    </el-row>
                </div>
                <div class="line" v-if="detail.is_continuous == '1'"></div>
                <div class="testinfo" v-if="detail.is_continuous == '1'">
                    <el-row>
                        <el-col :span="8">考试名称：{{detail.exam.name}}</el-col>
                        <el-col :span="8">报考专业：{{detail.domain}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">报考级别：{{detail.continuous_level }}</el-col>
                        <el-col :span="8">考试曲目1：</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">考试曲目2：</el-col>
                        <el-col :span="8">考试曲目3：</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">考试曲目4：</el-col>
                        <el-col :span="8">考试曲目5：</el-col>
                    </el-row>
                </div>
                <div class="line"></div>
                <div class="testinfo">
                    <el-row>
                        <el-col :span="7">填表人：{{detail.preparer }}</el-col>
                        <el-col :span="8">指导老师：{{detail.adviser }}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">老师电话：{{detail.adviser_phone }}</el-col>
                    </el-row>
                </div>
                <div class="line"></div>
                <div class="verifyresult" v-if="status.status == '1'">
                    <el-row>
                        <el-col :span="6" :offset="2">
                            <span>审核结果：</span>
                            <template>
                                <el-radio v-model="verifystatus" label="4" >通过</el-radio>
                                <el-radio v-model="verifystatus" label="2" >不通过</el-radio>
                            </template>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="6" :offset="4">
                            <el-button @click="cancel">取消</el-button>
                            <el-button @click="verify">确定</el-button>
                        </el-col>
                    </el-row>
                </div>
            </el-main>
        </el-container>
        <el-container class="payinfo" v-if="status.status !== '1'">
            <el-header>
                <el-row>
                    缴费信息
                </el-row>
            </el-header>
            <el-main>
                <div class="testinfo">
                    <el-row>
                        <el-col :span="8">缴费状态：{{statusType[detail.pay.status] }}</el-col>
                        <el-col :span="8" v-if="detail.pay.domain[0]">缴费科目：{{detail.pay.domain[0].name}}</el-col>
                        <el-col :span="4" :pull="2" v-if="detail.pay.domain[0]">{{detail.pay.domain[0].price}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">缴费方式：{{payType[detail.pay.type]}}</el-col>
                        <el-col :span="8" :push="1" v-if="detail.pay.domain[1]">{{detail.pay.domain[1].name}}</el-col>
                        <el-col :span="4" v-if="detail.pay.domain[1]" :pull="2">{{detail.pay.domain[1].price}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">缴费时间：{{detail.pay.pay_time }}</el-col>
                        <el-col :span="8">缴费金额：{{detail.pay.price }}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">缴费单号：{{detail.pay.number}}</el-col>
                    </el-row>
                    <el-button type="primary" plain v-if="status.plan == '3' || status.plan == '2'"  @click="getSignTable">查看考生报名评审表</el-button>
                </div>
                
            </el-main>
        </el-container>
        <el-container class="positioninfo" v-if="status.status == '3' || status.plan == '4'">
            <el-header>
                <el-row>
                    考场信息
                </el-row>
            </el-header>
            <el-main>
                <div class="testinfo">
                    <el-row>
                        <el-col :span="8">考试地址：{{detail.examsite1 && detail.examsite1.address}}</el-col>
                        <el-col :span="8">考场：{{detail.examsite1 && detail.examsite1.room}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">排位号：{{detail.examsite1 && detail.examsite1.sort}}</el-col>
                        <el-col :span="8">考试时间：{{detail.examsite1 && detail.examsite1.exam_time }}</el-col>
                    </el-row>
                    </div>
                    <div class="line"></div>
                    <div class="testinfo">
                    <el-row>
                        <el-col :span="8">考试地址：{{detail.examsite2 && detail.examsite2.address}}</el-col>
                        <el-col :span="8">考场：{{detail.examsite2 && detail.examsite2.room}}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8">排位号：{{detail.examsite2 && detail.examsite2.sort}}</el-col>
                        <el-col :span="8">考试时间：{{detail.examsite2 && detail.examsite2.exam_time }}</el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="8"><el-button type="primary" plain  @click="getSignTable">查看考生报名评审表</el-button></el-col>
                        <el-col :span="8"><el-button type="primary" plain  @click="getExam">查看考生准考证</el-button></el-col>
                    </el-row>
                    <div class="verifyresult" v-if="status">
                        <el-row>
                            <el-col :span="6" :offset="2">
                                <span>是否缺考顺延：</span>
                                <template>
                                    <el-radio v-model="radio" label="0" >否</el-radio>
                                    <el-radio v-model="radio" label="1" >是</el-radio>
                                </template>
                            </el-col>
                        </el-row>
                        <el-row>
                            <el-col :span="6" :offset="4">
                                <el-button @click="backlost">  返回  </el-button>
                                <el-button @click="confirmlost">  确定  </el-button>
                            </el-col>
                        </el-row>
                    </div>
                </div>
            </el-main>
        </el-container>
        <el-footer>

        </el-footer>
    </el-container>
</template>
<script>
import {mapState} from 'vuex'
export default {
    data() {
        return {
            radio: '0',
            payType: {'1': '微信支付','2':'线下缴费'},
            statusType: {'0': '未交费','1':'已缴费'},
            isLoading: false,
            verifystatus: '4',
        }
    },
    mounted(){
        this.$nextTick(()=> {
            this.getDetail()
        })
    },
    computed: {
        ...mapState('signup',{
            detail: state=> state.signDetail,
            status: state=> state.status
        })
    },
    methods: {
        //缺考顺延
        backlost: function() {
            this.$router.go(-1)
        },
        //确定缺考顺延操作
        confirmlost: function() {
            if(this.radio) {
                const apply_id = detail.id
                this.$axios({
                    url: '/apply/prolong',
                    method: 'post',
                    data:{apply_id}
                }).then(res=> {
                    this.$router.go(-1)
                    if(res && !res.error) {
                        this.$message.success(res.msg)
                    } else {
                        this.$message.warning(res.msg)
                    }
                }).catch(err=> {
                    console.log(err);
                })
            } else {
                this.$router.go(-1)
            }
        },
        //获取考生详情信息
        getDetail: function(){
            //若从列表页进入则清除，若从下载页返回则不需要清除状态数据
            if(this.$route.params.id) {
                console.log('进入清除状态',this.$route.params);
                //清除上次记录的报名信息状态
                this.$store.commit('signup/setSignDetail',{})
                //获取基本展示参数
                this.$store.commit('signup/setStatus',this.$route.params)
                const id = this.$route.params.id
                this.getSignDetail(id)
            } else {
                this.isLoading = true
            }
        },
        //发送获取详情数据请求
        getSignDetail: function(id){
            this.$axios({
                url: '/apply/detail',
                method: 'post',
                data: {id}
            }).then(res=> {
                console.log('获取到考生详情数据',res);
                if(res && !res.error) {
                    this.$nextTick(()=> {
                        this.$store.commit('signup/setSignDetail',res.data)
                        this.radio = res.data.postpone
                        this.isLoading = true
                    })
                } else {
                    this.$message.warning(res.msg)
                }
            }).catch(err=> {
                console.log(err);
            })
        },
        //取消考生审核
        cancel: function(){
            this.$router.go(-1)
        },
        //通过审核
        verify: function(){
            const status = this.verifystatus
            const id = this.status.id
            if(status && id) {
                this.$axios({
                    url: '/apply/check',
                    method: 'post',
                    data: {id,status}
                }).then(res=> {
                    console.log('发送审核请求',res);
                    if(res && !res.error) {
                        this.$message.success(res.msg)
                    } else {
                        this.$message.warning(res.msg)
                    }
                    this.$router.push({
                            name: 'signup'
                        })
                }).catch(err=> {
                    console.log(err);
                })
            }
        },
        //查看专业证书
        getProfessional(){
            this.$router.push({
                name: 'imginfo',
                params: {
                    imgurl: [this.detail.pro_certificate_url ]
                }
            })
        },
        //查看基本乐科证书
        getbase(){
            this.$router.push({
                name: 'imginfo',
                params: {
                    imgurl: [this.detail.basic_certificate_url ] 
                }
            })
        },
        //查看报名评审表
        getSignTable(){
            const imgurl =[]
            if(this.detail.bm_continuous_image_url) imgurl.push(this.detail.bm_continuous_image_url)
            if(this.detail.bm_image_url) imgurl.push(this.detail.bm_image_url)
            if(this.detail.kz_image_url) imgurl.push(this.detail.kz_image_url)
            this.$router.push({
                name: 'imginfo',
                params: {
                    imgurl: imgurl
                }
            })
        },
        //查看准考证
        getExam(){
            this.$router.push({
                name: 'imginfo',
                params: {
                    imgurl: [this.detail.kz_url] 
                }
            })
        },
        
    }
}
</script>

<style lang="scss" scoped>
    .el-container {
        font-size: 18px;
        .signinfo,.payinfo,.positioninfo {
            margin-top: 40px;
            .el-header {
                background-color: #545c64;
                height: 50px!important; 
                .el-row {
                    font-size: 18px;
                    color: #fff;
                    line-height: 50px;
                }
            }
            .el-main {
                .baseinfo,.testinfo {
                    padding-left: 10px;
                    .el-row{
                        margin: 20px 0;
                        .examinee-img{
                            width: 127px;
                            height: 182px;
                        }
                    }
                }
                .line {
                    width: 100%;
                    height: 1px;
                    margin: 10px;
                    background-color: #ddd;
                }
                .verifyresult{
                    margin-left: 40px;
                    margin-top: 40px;
                    .el-row{
                        margin-bottom: 20px;
                        &:last-child {
                            margin-top: 40px;
                        }
                    }
                    
                    span{
                        font-weight: 700;
                        font-size: 22px;
                        .el-radio {
                            margin-left: 40px;
                            font-size: 20px;
                        }
                    }
                    .el-button {
                        font-size: 20px;
                    }
                }
                .el-button {
                    margin-left: 40px;
                    
                }
            }
        }
    }
</style>


