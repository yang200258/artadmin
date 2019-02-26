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
  </div>
</template>

<script>
import globalConstant from '../lib/globalConstant'
import Seal from '../components/Seal'

export default {
  data () {
    return {
      roles: globalConstant.roles,
      userRole: '0',
      showCerMajor: false,
      showCerBasicmusic: false,
      order: {
        status: '1',
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
      }
    }
  },
  components: { Seal },
  mounted () {
    console.log('this.$route', this.$route)
  },
  methods: {
    changeShowCer: function (key) {
      this[key] = !this[key]
    },
    requestDelay: function (callback) {
      // todo，请求缺考顺延
      // 模拟请求缺考顺延
      setTimeout(() => {
        callback && callback()
      }, 500)
    },
    enrollMore: function () { // 点击继续添加
      if ((this.userRole.toString() === this.roles.teacher || this.userRole.toString() === this.roles.institution) && !this.outOfExam && !this.editedDelay) { // 老师或机构 且 在考试时间内 且未请求过缺考顺延
        const successCallback = () => {
          this.$router.replace({ path: '/enroll/apply' })
        }
        this.requestDelay(successCallback)
      } else {
        this.$router.replace({ path: '/enroll/apply' })
      }
    },
    complete: function () { // 点击完成
      if ((this.userRole.toString() === this.roles.teacher || this.userRole.toString() === this.roles.institution) && !this.outOfExam && !this.editedDelay) { // 老师或机构 且 在考试时间内 且未请求过缺考顺延
        const successCallback = () => {
          this.$router.replace({ path: '/enroll/detail' })
        }
        this.requestDelay(successCallback)
      } else {
        this.$router.replace({ path: '/enroll/detail' })
      }
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
  position: relative;
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
.seal-box{
  position: absolute;
  top: 0;
  right: 92px;
}
</style>
