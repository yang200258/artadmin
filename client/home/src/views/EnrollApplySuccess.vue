<template>
  <div class="p-enroll-apply-success">
    <div class="title">提交成功</div>
    <div class="body">
      <i class="ykfont yk-success"></i>
      <div class="big-tip">报名提交成功，请等待审核</div>
      <div class="sub-tip">审核通过并进行缴费后完成报名</div>
      <div class="red-tip">关注微信公众号“海南考级中心”及时获取更多考试相关信息</div>
      <div class="btn cursor-pointer" @click.stop="goBack">完成</div>
    </div>
  </div>
</template>

<script>

export default {
  data () {
    return {}
  },
  methods: {
    goBack: function () {
      this.$router.go(-1)
    }
  }
}
</script>

<style scoped>
.p-enroll-apply-success{
  background: #fff;
}
.title{
  height: 50px;
  line-height: 50px;
  font-size: 18px;
  color: #76593D;
  padding: 0 26px;
  background: #FFF7EC;
}
.body{
  padding-bottom: 65px;
}
.yk-success{
  display: block;
  font-size: 106px;
  margin: 46px auto;
  width: 106px;
  overflow: visible;
  color: #5DC540;
}
.big-tip{
  font-size: 24px;
  line-height: 34px;
  text-align: center;
  color: #141619;
  font-weight: bold;
}
.sub-tip{
  font-size: 18px;
  line-height: 26px;
  color: #9B9B9B;
  text-align: center;
  margin-top: 16px;
}
.red-tip{
  font-size: 18px;
  line-height: 26px;
  color: #D0021B;
  text-align: center;
  margin-top: 50px;
}
.btn{
  width: 260px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  color: #795C41;
  background: #F2E0CA;
  border-radius: 5px;
  margin: 59px auto 0;
}
</style>
