<template>
  <div class="p-enroll-order">
    <div class="info-box">
      <div class="title">报名信息</div>
      <div class="info-body">
        <img v-if="order.evaSheet" class="image" :src="order.evaSheet" alt="报名评审表" title="报名评审表" />
      </div>
    </div>
    <div class="fee-box2">
      <div class="title">应缴费用</div>
      <div class="fee-body">
        <div v-for="row in order.infoList" :key="row.id" class="info-row2 clearfix">
          <div class="fl">考试项目：{{row.name}}</div>
          <div class="fr">{{row.price}}元</div>
        </div>
        <div class="total">总计：<span style="color:#D0021B">{{order.total}}</span>元</div>
      </div>
    </div>
    <div class="state-text2 clearfix">
      <i class="ykfont yk-warning fl"></i>
      <div class="warining-text fl">进行缴费后，考试费用不再退回</div>
    </div>
    <div class="go-pay-btn cursor-pointer" @click.stop="goPay">立即缴费</div>
  </div>
</template>

<script>

export default {
  data () {
    return {
      order: {
        evaSheet: 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1550047344237&di=1b411d36b7e87d1ced1fc3cf44a0cd1a&imgtype=0&src=http%3A%2F%2Fpic5.photophoto.cn%2F20071207%2F0033034132771700_b.jpg',
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
    goPay: function () {
      console.log('goPay')
      this.$router.replace({ path: '/enroll/pay' })
    }
  }
}
</script>

<style scoped>
.p-enroll-order{
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
.info-box,.fee-box2{
  background: #fff;
}
.fee-box2{
  background: #fff;
  margin-top: 25px;
}
.info-body,.fee-body2{
  padding: 30px 26px 45px;
}
.fee-body2{
  padding: 30px 26px 45px;
}
.image{
  width: 500px;
  background-color: #FFF7EC;
  border: none;
  display: block;
  margin: 0 auto;
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
.yk-warning{
  color: #D0021B;
}
.warining-text{
  color: #D0021B;
  padding-left: 8px;
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
