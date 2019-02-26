<template>
  <div class="c-bottom-info" :style="{height: height + 'px'}">
    <div class="c-bottom-bg"></div>
    <p class="bottom-text">隐私版权 | 联系我们 ：010-66425733 010-66414137<br />©️ Copyright 海南禹讯网络科技股份有限公司 All Rights Reserved<br />琼ICP备05064625号</p>
  </div>
</template>

<script>
export default {
  props: {
    height: {
      type: Number,
      default: 150
    }
  },
  data () {
    return {}
  }
}
</script>

<style scoped>
.c-bottom-info{
  height: 150px;
  overflow: visible;
  display: table;
  width: 100%;
  color: #fff;
  position: relative;
}
.bottom-text{
  display: table-cell;
  font-size: 16px;
  line-height: 24px;
  text-align: center;
  vertical-align: middle;
  position: relative;
  z-index: 2;
}
.c-bottom-bg{
  position: absolute;
  width: 300%;
  height: 100%;
  top: 0;
  left: -100%;
  z-index: 1;
  background-color: #4B4144;
  background-image: url('../assets/image/bottom_bg.png');
  background-position: center;
}
</style>
