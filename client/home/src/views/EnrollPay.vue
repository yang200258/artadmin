<template>
  <div class="p-enroll-pay">
    <div class="info-box">
      <div class="title">确认支付</div>
      <div class="info-body">
        <div class="info-top">
          <div class="clearfix">
            <i class="ykfont yk-success fl"></i>
            <div class="big-tip fl">订单提交成功，现在只差最后一步啦！</div>
          </div>
          <div class="sub-tip">请在规定报名时间内完成缴费，若逾期未缴费，视为放弃报名！</div>
        </div>
        <div class="info-bottom">
          <div class="info-item">考生姓名：{{enroll.name}}</div>
          <!-- <div class="info-item">报考专业：{{order.major}}</div>
          <div class="info-item">报考等级：{{order.level}}</div> -->
          <div v-for="row in payData.domain" :key="row.id" class="info-row2 clearfix">
            <div class="fl">考试项目：{{row.name}}</div>
            <div class="fr">{{row.price || '0.00'}}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="fee-box">
      <div class="title2">支付金额：<span style="color:#D0021B;font-size:20px;font-weight:bold">{{payData.price}}</span>元</div>
      <div class="fee-body clearfix">
        <div class="fee-left fl">
          <div class="pay-title">微信支付</div>
          <img v-if="payurl" class="pay-qrcode" :src="'/pay/qrcode?data=' + payurl" />
          <div class="qrcode-tip clearfix">
            <i class="ykfont yk-scancode qrcode-tip-icon fl"></i>
            <div class="qrcode-tip-text fl">请使用微信"扫一扫"<br />扫描二维码支付</div>
          </div>
        </div>
        <div class="fee-right fr"></div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'EnrollPay',
  data () {
    return {
      enroll: {},
      payData: {},
      payurl: '',
      varifyTimer: null
    }
  },
  mounted () {
    this.requestPayment()
  },
  beforeDestroy () {
    if (this.varifyTimer) {
      clearTimeout(this.varifyTimer)
    }
  },
  methods: {
    requestPayment: function () {
      console.log('requestPayment')
      let rData = {
        apply_id: this.$route.query.id
      }
      this.$ajax('/pay', { data: rData }).then(res => {
        console.log('/pay', res)
        if (res && res.data && !res.error) { // 获取支付数据成功
          this.enroll = res.data.apply
          this.payData = res.data.apply.pay
          this.payurl = res.data.payurl
          this.varifyPayment()
        }
      }).catch(err => {
        console.log('/pay_err', err)
      })
    },
    varifyPayment: function () {
      console.log('varifyPayment')
      if (this.varifyTimer) {
        clearTimeout(this.varifyTimer)
      }
      let rData = {
        apply_id: this.$route.query.id
      }
      this.$ajax('/pay/queryorder', { data: rData, dontToast: true }).then(res => {
        console.log('/pay/queryorder', res)
        if (res && (res.error === 0 || res.error === '0')) { // 支付成功
          if (this.varifyTimer) {
            clearTimeout(this.varifyTimer)
          }
          this.$router.replace({ path: '/enroll/detail', query: { id: this.$route.query.id } })
        } else {
          if (this.varifyTimer) {
            clearTimeout(this.varifyTimer)
          }
          this.varifyTimer = setTimeout(this.varifyPayment, 2000)
        }
      }).catch(err => {
        console.log('/pay/queryorder_err', err)
        if (this.varifyTimer) {
          clearTimeout(this.varifyTimer)
        }
        this.varifyTimer = setTimeout(this.varifyPayment, 2000)
      })
    }
  }
}
</script>

<style scoped>
.p-enroll-pay{
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
.title2{
  height: 50px;
  line-height: 50px;
  font-size: 18px;
  color: #141619;
  margin: 0 26px;
  border-bottom: 1px solid #979797;
}
.info-box,.fee-box{
  background: #fff;
}
.fee-box{
  margin-top: 25px;
}
.info-body,.fee-body{
  padding: 16px 26px;
}
.total{
  font-size: 20px;
  line-height: 28px;
  color: #141619;
  margin-top: 30px;
  text-align: right;
  font-weight: bold;
}
.yk-success{
  display: block;
  font-size: 44px;
  line-height: 44px;
  color: #5DC540;
  margin-right: 13px;
}
.big-tip{
  font-size: 18px;
  line-height: 44px;
  color: #141619;
}
.sub-tip{
  font-size: 16px;
  line-height: 22px;
  color: #141619;
  padding: 10px 0 24px;
}
.info-top{
  border-bottom: 1px solid #979797;
}
.info-bottom{
  font-size: 16px;
  color: #141619;
  padding: 16px 0 6px;
}
.info-item{
  padding: 8px 0;
}
.fee-left{
  width: 350px;
  margin-left: 100px;
}
.pay-title{
  font-size: 18px;
  line-height: 26px;
  color: #141619;
  text-align: center;
  padding-bottom: 16px;
}
.pay-qrcode{
  display: block;
  width: 100%;
  height: 350px;
  background: #EEEEEE;
}
.qrcode-tip{
  width: 254px;
  margin: 24px auto 0;
}
.qrcode-tip-icon{
  font-size: 50px;
  color: #76593D;
  margin-right: 12px;
}
.qrcode-tip-text{
  font-size: 20px;
  line-height: 28px;
  color: #76593D;
}
.fee-right{
  width: 300px;
  height: 384px;
  background: url('../assets/image/scan_guide.png') no-repeat;
  background-size: contain;
  background-position: center;
  margin-right: 100px;
  margin-top: 24px;
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
</style>
