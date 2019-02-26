<template>
  <div class="c-seal" :style="{borderColor: sealColor[type]}">
    <div class="seal-box" :style="{borderColor: sealColor[type], color: sealColor[type]}">{{sealText[type]}}</div>
  </div>
</template>

<script>
import globallConstant from '../lib/globalConstant'
export default {
  props: {
    type: {
      type: String,
      required: true
    }
  },
  data () {
    return {
      sealText: globallConstant.sealText,
      sealColor: globallConstant.sealColor
    }
  }
}
</script>

<style scoped>
.c-seal{
  width: 194px;
  height: 194px;
  border-style: solid;
  border-width: 3px;
  border-color: transparent;
  border-radius: 50%;
  opacity: 0.8;
}
.seal-box{
  width: 170px;
  height: 170px;
  line-height: 170px;
  font-size: 46px;
  text-align: center;
  border-style: solid;
  border-width: 6px;
  border-color: transparent;
  margin: 6px;
  border-radius: 50%;
  transform: rotate(-20deg);
}
</style>
