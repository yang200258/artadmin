<template>
  <div class="p-enroll-detail">
    <div class="info-box">
      <div class="title">报名信息</div>
      <div class="info-body">
        <img v-if="order.evaSheet" class="image" :src="order.evaSheet" alt="报名评审表" title="报名评审表" />
        <div class="seal-box">
          <seal :type="order.status.toString()" />
        </div>
        <div v-if="order.cerMajor || order.cerBasicmusic" class="cer-buttons clearfix">
          <div v-if="order.cerMajor" class="cer-button cursor-pointer fl" @click.stop="changeShowCer('showCerMajor')">查看专业证书</div>
          <div v-if="order.cerBasicmusic" class="cer-button cursor-pointer fl" @click.stop="changeShowCer('showCerBasicmusic')">查看基本乐科证书</div>
        </div>
      </div>
      <div v-if="order.cerMajor" v-show="showCerMajor" class="cer-wrapper">
        <div class="cer-box" :style="{backgroundImage: 'url(' + order.cerMajor + ')'}"></div>
        <i class="ykfont yk-close cer-close cursor-pointer" @click.stop="changeShowCer('showCerMajor')"></i>
      </div>
      <div v-if="order.cerBasicmusic" v-show="showCerBasicmusic" class="cer-wrapper">
        <div class="cer-box" :style="{backgroundImage: 'url(' + order.cerBasicmusic + ')'}"></div>
        <i class="ykfont yk-close cer-close cursor-pointer" @click.stop="changeShowCer('showCerBasicmusic')"></i>
      </div>
    </div>
    <div v-if="order.status.toString() === '5'" class="fee-box">
      <div class="title">缴费信息</div>
      <div class="fee-body">
        <div class="info-row">缴费状态：{{statusText[order.status]}}</div>
        <div class="clearfix">
          <div class="info-row fl">缴费科目：</div>
          <div class="fl" style="width:870px">
            <div v-for="row in order.infoList" :key="row.id" class="info-row clearfix">
              <div class="name fl">{{row.name}}</div>
              <div class="price fr">{{row.price}}元</div>
            </div>
          </div>
        </div>
        <div class="info-row">缴费金额：{{order.total}}元</div>
        <div class="info-row">缴费方式：{{order.methodText}}</div>
        <div class="info-row">缴费时间：{{order.payTime}}</div>
        <div class="info-row">缴费单号：{{order.payNumber}}</div>
      </div>
    </div>
    <div v-else-if="order.status.toString() === '2'" class="fee-box2">
      <div class="title">应缴费用</div>
      <div class="fee-body">
        <div v-for="row in order.infoList" :key="row.id" class="info-row2 clearfix">
          <div class="fl">考试项目：{{row.name}}</div>
          <div class="fr">{{row.price}}元</div>
        </div>
        <div class="total">总计：<span style="color:#D0021B">{{order.total}}</span>元</div>
      </div>
    </div>
    <div v-if="order.status.toString() === '2'">
      <div class="state-text2 clearfix">
        <i class="ykfont yk-warning fl"></i>
        <div class="warining-text fl">进行缴费后，考试费用不再退回</div>
      </div>
      <div class="go-pay-btn cursor-pointer" @click.stop="goPay">立即缴费</div>
    </div>
    <div v-else-if="order.status.toString() === '5'">
      <div v-if="hall && hall.id" class="hall-box">
        <div class="title">考场信息</div>
        <div class="hall-body">
          <div class="info-row">考试地址：{{hall.address}}元</div>
          <div class="info-row">考场：{{hall.name}}</div>
          <div class="info-row">排位号：{{hall.seat}}</div>
          <div class="info-row">考试时间：{{hall.examTime}}</div>
        </div>
      </div>
      <!-- <div v-if="(userRole.toString() === roles.teacher || userRole.toString() === roles.institution) && !outOfExam && !editedDelay" class="delay-box clearfix">
        <div class="required-title fl">是否缺考顺延：</div>
        <label class="delay-label cursor-pointer fl clearfix">
          <input type="radio" class="delay-radio cursor-pointer fl" value="0" v-model="delay" />
          <span class="fl">否</span>
        </label>
        <label class="delay-label cursor-pointer fl clearfix">
          <input type="radio" class="delay-radio cursor-pointer fl" value="1" v-model="delay" />
          <span class="fl">是</span>
        </label>
      </div> -->
      <div class="bottom-buttons">
        <div class="cer-buttons clearfix">
          <div v-if="userRole.toString() === roles.teacher || userRole.toString() === roles.institution" class="bottom-button cursor-pointer fl" @click.stop="enrollMore">继续添加</div>
          <div class="bottom-button cursor-pointer fl" @click.stop="complete">完成</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import globalConstant from '../lib/globalConstant'
import Seal from '../components/Seal'

export default {
  data () {
    return {
      statusText: globalConstant.statusText,
      roles: globalConstant.roles,
      userRole: '0',
      showCerMajor: false,
      showCerBasicmusic: false,
      order: {
        status: '5',
        methodText: '微信支付/线下缴费',
        payTime: '2019.01.02 14：35：30',
        payNumber: '',
        cerMajor: 'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=1039122920,2898171750&fm=26&gp=0.jpg', // 专业证书
        cerBasicmusic: 'https://ss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=3370886825,148288012&fm=26&gp=0.jpg', // 基本乐科证书
        evaSheet: 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1550047344237&di=1b411d36b7e87d1ced1fc3cf44a0cd1a&imgtype=0&src=http%3A%2F%2Fpic5.photophoto.cn%2F20071207%2F0033034132771700_b.jpg', // 评审表
        infoList: [
          {
            id: '2',
            name: '基本乐科一级',
            price: '240.00'
          },
          {
            id: '3',
            name: '基本乐科二级',
            price: '280.00'
          }
        ],
        total: '520.00'
      },
      hall: {
        id: '1',
        address: '海南省海口市龙华区滨海大道105号百方广场15B',
        name: '考场1',
        seat: '35',
        examTime: '2019.02.15 14:00'
      },
      outOfExam: false, // 考试时间是否已过
      editedDelay: false, // 是否已提交过缺考顺延
      delay: '0' // 缺考是否顺延
    }
  },
  components: { Seal },
  methods: {
    changeShowCer: function (key) {
      this[key] = !this[key]
    },
    goPay: function () {
      console.log('goPay')
      this.$router.replace({ path: '/enroll/pay' })
    },
    requestDelay: function (callback) {
      // todo，请求缺考顺延
      // 模拟请求缺考顺延
      setTimeout(() => {
        callback && callback()
      }, 500)
    },
    enrollMore: function () { // 点击继续添加
      this.$router.replace({ path: '/enroll/apply' })
      // if ((this.userRole.toString() === this.roles.teacher || this.userRole.toString() === this.roles.institution) && !this.outOfExam && !this.editedDelay) { // 老师或机构 且 在考试时间内 且未请求过缺考顺延
      //   const successCallback = () => {
      //     this.$router.replace({ path: '/enroll/apply' })
      //   }
      //   this.requestDelay(successCallback)
      // } else {
      //   this.$router.replace({ path: '/enroll/apply' })
      // }
    },
    complete: function () { // 点击完成
      this.$router.go(-1)
      // if ((this.userRole.toString() === this.roles.teacher || this.userRole.toString() === this.roles.institution) && !this.outOfExam && !this.editedDelay) { // 老师或机构 且 在考试时间内 且未请求过缺考顺延
      //   const successCallback = () => {
      //     this.$router.go(-1)
      //   }
      //   this.requestDelay(successCallback)
      // } else {
      //   this.$router.go(-1)
      // }
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
.state-text{
  font-size: 16px;
  line-height: 22px;
  margin: 30px 0 0 24px;
}
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
  background-color: transparent;
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
