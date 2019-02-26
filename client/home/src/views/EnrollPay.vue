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
          <div class="info-item">考生姓名：{{order.username}}</div>
          <div class="info-item">报考专业：{{order.major}}</div>
          <div class="info-item">报考等级：{{order.level}}</div>
        </div>
      </div>
    </div>
    <div class="fee-box">
      <div class="title2">支付金额：<span style="color:#D0021B;font-size:20px;font-weight:bold">{{order.total}}</span>元</div>
      <div class="fee-body clearfix">
        <div class="fee-left fl">
          <div class="pay-title">微信支付</div>
          <img class="pay-qrcode" />
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
  data () {
    return {
      order: {
        username: '张三',
        major: '钢琴',
        level: '表演文凭级',
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
  methods: {

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
</style>
