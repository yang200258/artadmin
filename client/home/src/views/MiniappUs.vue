<template>
  <div class="p-us">
    <div class="us-detail-body">
      <div class="header">
        <div class="title">{{aboutUs.title}}</div>
        <div v-if="aboutUs.intro" class="intro">{{aboutUs.intro}}</div>
      </div>
      <div class="us-content" v-html="aboutUs.content"></div>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import mixin from '../lib/mixins'

export default {
  mixins: [mixin],
  data () {
    return {
      aboutUs: {}
    }
  },
  mounted: function () {
    this.fetchAboutUs()
  },
  methods: {
    fetchAboutUs: function () {
      this.$ajax('/msg/detail', { data: { cid: 6 } }).then(res => { // cid为6表示 关于我们 的数据
        if (res && !res.error) { // 获取成功
          this.aboutUs = res.data
        }
      }).catch(err => {
        console.log('err', err)
      })
    }
  }
}
</script>

<style scoped>
.p-us{
  padding: 0 15px 20px;
  overflow: hidden;
}
.title{
  font-size: 23px;
  line-height: 33px;
  color: #333;
  font-weight: bold;
  padding-top: 30px;
  padding-bottom: 15px;
  text-align: center;
}
.intro{
  font-size: 16px;
  line-height: 22px;
  color: #999;
  padding: 15px 0;
  text-align: left;
}
.us-content /deep/ p,.us-content /deep/ div{
  line-height: 175% !important;
  word-break: break-word;
  white-space: pre-wrap;
}
.us-content /deep/ img{
  max-width: 100% !important;
  display: block;
  margin: 0 auto;
}
</style>
