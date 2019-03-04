<template>
  <div class="p-enroll-detail">
    <div class="info-box">
      <div class="title">报名信息</div>
      <div class="info-body">
        <img v-if="enroll.bm_image_url" class="image" :src="enroll.bm_image_url" alt="报名评审表" title="报名评审表" />
        <div v-if="enroll.plan" class="seal-box">
          <seal :type="enroll.plan.toString()" />
        </div>
        <div v-if="enroll.pro_certificate_url || enroll.basic_certificate_url" class="cer-buttons clearfix">
          <div v-if="enroll.pro_certificate_url" class="cer-button cursor-pointer fl" @click.stop="changeShowCer('showCerMajor')">查看专业证书</div>
          <div v-if="enroll.basic_certificate_url" class="cer-button cursor-pointer fl" @click.stop="changeShowCer('showCerBasicmusic')">查看基本乐科证书</div>
        </div>
      </div>
      <div v-if="enroll.pro_certificate_url" v-show="showCerMajor" class="cer-wrapper">
        <div class="cer-box" :style="{backgroundImage: 'url(' + enroll.pro_certificate_url + ')'}"></div>
        <i class="ykfont yk-close cer-close cursor-pointer" @click.stop="changeShowCer('showCerMajor')"></i>
      </div>
      <div v-if="enroll.basic_certificate_url" v-show="showCerBasicmusic" class="cer-wrapper">
        <div class="cer-box" :style="{backgroundImage: 'url(' + enroll.basic_certificate_url + ')'}"></div>
        <i class="ykfont yk-close cer-close cursor-pointer" @click.stop="changeShowCer('showCerBasicmusic')"></i>
      </div>
    </div>
    <!-- 已缴费 -->
    <div v-if="enroll.plan && enroll.plan.toString() === '4'" class="fee-box">
      <div class="title">缴费信息</div>
      <div class="fee-body">
        <div class="info-row">缴费状态：{{statusText[enroll.plan]}}</div>
        <div class="clearfix">
          <div class="info-row fl">缴费科目：</div>
          <div class="fl" style="width:870px">
            <div v-for="row in payData.domain" :key="row.id" class="info-row clearfix">
              <div class="name fl">{{row.name}}</div>
              <div class="price fr">{{row.price || '0.00'}}</div>
            </div>
          </div>
        </div>
        <div class="info-row">缴费金额：{{payData.price || '0.00'}}元</div>
        <div class="info-row">缴费方式：{{payType[payData.type.toString()]}}</div>
        <div class="info-row">缴费时间：{{payData.pay_time}}</div>
        <div class="info-row">缴费单号：{{payData.number}}</div>
      </div>
    </div>
    <!-- 待缴费 -->
    <div v-else-if="enroll.plan && enroll.plan.toString() === '2'" class="fee-box2">
      <div class="title">应缴费用</div>
      <div class="fee-body">
        <div v-for="row in payData.domain" :key="row.id" class="info-row2 clearfix">
          <div class="fl">考试项目：{{row.name}}</div>
          <div class="fr">{{row.price || '0.00'}}</div>
        </div>
        <div class="total">总计：<span style="color:#D0021B">{{payData.price}}</span>元</div>
      </div>
    </div>
    <div v-if="enroll.plan && enroll.plan.toString() === '2'">
      <div class="state-text2 clearfix">
        <i class="ykfont yk-warning fl"></i>
        <div class="warining-text fl">进行缴费后，考试费用不再退回</div>
      </div>
      <div class="go-pay-btn cursor-pointer" @click.stop="goPay">立即缴费</div>
    </div>
    <!-- 失效原因 -->
    <div v-if="enroll.cause">
      <div class="state-text2 clearfix">
        <div class="warining-text fl">失效原因：{{enroll.cause}}</div>
      </div>
    </div>
    <!-- 考场信息 -->
    <div v-if="enroll.examsite1" class="hall-box">
      <div class="title">考场信息</div>
      <div class="hall-body">
        <div class="info-row">考试地址：{{enroll.examsite1.address}}</div>
        <div class="info-row">考场：{{enroll.examsite1.room}}</div>
        <div class="info-row">排位号：{{enroll.examsite1.sort}}</div>
        <div class="info-row">考试时间：{{enroll.examsite1.exam_time}}</div>
      </div>
    </div>
    <div v-if="enroll.examsite2" class="hall-box">
      <div class="title">考场信息</div>
      <div class="hall-body">
        <div class="info-row">考试地址：{{enroll.examsite2.address}}</div>
        <div class="info-row">考场：{{enroll.examsite2.room}}</div>
        <div class="info-row">排位号：{{enroll.examsite2.sort}}</div>
        <div class="info-row">考试时间：{{enroll.examsite2.exam_time}}</div>
      </div>
    </div>
    <!-- 已缴费 -->
    <div v-else-if="enroll.plan && enroll.plan.toString() === '4'">
      <div class="bottom-buttons">
        <div class="cer-buttons clearfix">
          <div v-if="storeUD.userType === roles.teacher || storeUD.userType === roles.institution" class="bottom-button cursor-pointer fl" @click.stop="enrollMore">继续添加</div>
          <div class="bottom-button cursor-pointer fl" @click.stop="complete">完成</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import globalConstant from '../lib/globalConstant'
import Seal from '../components/Seal'

export default {
  name: 'enrollDetail',
  data () {
    return {
      statusText: globalConstant.statusText,
      roles: globalConstant.roles,
      payType: globalConstant.enrollPayType,
      showCerMajor: false,
      showCerBasicmusic: false,
      enroll: {},
      payData: {}
    }
  },
  computed: {
    ...mapState('user', ['storeUD'])
  },
  components: { Seal },
  // mounted () {
  //   this.fetchEnroll()
  // },
  mounted () {
    this.fetchEnroll()
  },
  methods: {
    changeShowCer: function (key) {
      this[key] = !this[key]
    },
    goPay: function () {
      console.log('goPay')
      this.$router.replace({ path: '/enroll/pay', query: { id: this.$route.query.id } })
    },
    enrollMore: function () { // 点击继续添加
      this.$router.replace({ path: '/enroll/apply' })
    },
    complete: function () { // 点击完成
      this.$router.go(-1)
    },
    fetchEnroll: function () {
      console.log('fetchEnroll')
      let rData = {
        id: this.$route.query.id
      }
      this.$ajax('/apply/detail', { data: rData }).then(res => {
        console.log('/apply/detail', res)
        if (res && res.data && !res.error) {
          this.enroll = res.data
          this.payData = res.data.pay
        }
      }).catch(err => {
        console.log('/apply/detail_error', err)
      })
    }
  }
}
</script>

<style scoped>
.p-enroll-detail{
  padding-bottom: 45px;
}
.title{
  height: 50px;
  line-height: 50px;
  font-size: 18px;
  color: #76593D;
  padding: 0 26px;
  background: #FFF7EC;
}
.info-box,.fee-box,.hall-box{
  background: #fff;
  position: relative;
}
.fee-box,.hall-box{
  margin-top: 25px;
}
.info-body{
  padding: 30px 26px 45px;
  text-align: center;
}
.fee-body{
  padding: 14px 26px 29px;
}
.hall-body{
  padding: 14px 26px 29px;
}
.image{
  width: 500px;
  background-color: #FFF7EC;
  border: none;
  display: block;
  margin: 0 auto;
}
.info-row{
  font-size: 16px;
  line-height: 22px;
  color: #141619;
  margin-top: 16px;
}
.total{
  font-size: 20px;
  line-height: 28px;
  color: #141619;
  margin-top: 30px;
  text-align: right;
  font-weight: bold;
}
/* .state-text{
  font-size: 16px;
  line-height: 22px;
  margin: 30px 0 0 24px;
} */
.yk-warning{
  color: #D0021B;
}
.warining-text{
  color: #D0021B;
  padding-left: 8px;
}
.btn{
  width: 200px;
  height: 40px;
  line-height: 40px;
  background: #BB3F3F;
  font-size: 20px;
  color: #fff;
  text-align: center;
  border-radius: 5px;
  margin: 50px auto 0;
}
.cer-buttons{
  display: inline-block;
  margin-top: 40px;
}
.cer-button{
  width: 148px;
  height: 38px;
  line-height: 38px;
  border: 1px solid #979797;
  border-radius: 5px;
  font-size: 16px;
  color: #795C41;
  text-align: center;
  margin-left: 150px;
}
.cer-button:first-child{
  margin-left: 0;
}
.cer-wrapper{
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  background: rgba(0,0,0,0.6);
}
.cer-box{
  position: absolute;
  left: 50px;
  top: 100px;
  right: 50px;
  bottom: 100px;
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center;
  background-color: #E5E5E5;
}
.cer-close{
  position: absolute;
  left: 50%;
  margin-left: -12px;
  bottom: 30px;
  font-size: 32px;
  color: #fff;
}
.delay-box{
  padding-top: 26px;
  padding-left: 26px;
}
.delay-label{
  padding: 0 20px;
  margin: 0 24px;
  font-size: 16px;
  line-height: 22px;
}
.delay-radio{
  display: block;
  margin: 1px 10px 0 0;
  width: 16px;
  height: 16px;
  padding: 0;
}
.bottom-buttons{
  text-align: center;
}
.bottom-button{
  width: 148px;
  height: 38px;
  line-height: 38px;
  border: 1px solid #979797;
  border-radius: 5px;
  font-size: 16px;
  color: #141619;
  text-align: center;
  margin-left: 150px;
  background: #fff;
}
.bottom-button:first-child{
  margin-left: 0;
}
.seal-box{
  position: absolute;
  top: 0;
  right: 92px;
}

.fee-box2{
  background: #fff;
  margin-top: 25px;
}
.fee-body2{
  padding: 30px 26px 45px;
}
.info-row2{
  font-size: 16px;
  line-height: 22px;
  color: #141619;
  margin-top: 30px;
}
.info-row2:first-child{
  margin-top: 16px;
}
.total2{
  font-size: 20px;
  line-height: 28px;
  color: #141619;
  margin-top: 30px;
  text-align: right;
  font-weight: bold;
}
.state-text2{
  font-size: 16px;
  line-height: 22px;
  margin: 30px 0 0 24px;
}
.go-pay-btn{
  width: 200px;
  height: 40px;
  line-height: 40px;
  background: #BB3F3F;
  font-size: 20px;
  color: #fff;
  text-align: center;
  border-radius: 5px;
  margin: 50px auto 0;
}
</style>
