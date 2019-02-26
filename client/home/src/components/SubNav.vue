<template>
  <div class="c-sub-nav">
    <div class="c-sub-nav-bg"></div>
    <div class="c-sub-nav-content clearfix">
      <div class="nav-section fl">中国音乐学院考级委员会</div>
      <div class="nav-section fl" v-for="(nav, idx) in navs" :key="idx" @click.stop="navClick(nav, idx)"><span style="padding-right:8px">></span><span class="nav-link" :class="{disabled: idx === navs.length - 1}">{{nav.label}}</span></div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    navs: {
      type: Array,
      default: function () {
        return []
      }
    }
  },
  methods: {
    navClick: function (nav, idx) {
      if (idx === this.navs.length - 1) { // 最后一个不响应
        return false
      }
      if (nav.name) {
        this.$router.replace({ name: nav.name })
      } else if (nav.link) {
        this.$router.replace({ path: nav.link })
      }
    }
  }
}
</script>

<style scoped>
.c-sub-nav{
  position: relative;
  overflow-x: visible;
}
.c-sub-nav-bg{
  position: absolute;
  width: 300%;
  height: 100%;
  left: -100%;
  top: 0;
  background: #795C41;
}
.c-sub-nav-content{
  padding: 12px 0;
  font-size: 18px;
  line-height: 26px;
  color: #fff;
  position: relative;
  z-index: 2;
}
.nav-section{
  padding-right: 8px;
}
.nav-link{
  cursor: pointer;
}
.nav-link:hover{
  color: #F3E6D5;
  text-decoration: underline;
}
.nav-link.disabled{
  color: #fff;
  cursor: text;
  text-decoration: none;
}
</style>
