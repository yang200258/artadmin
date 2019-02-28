<template>
  <div class="p-regulation-detail">
    <div class="regulation-detail-body">
      <div class="header">
        <div class="title">{{regulation.title}}</div>
        <div v-if="regulation.intro" class="intro">{{regulation.intro}}</div>
      </div>
      <div class="regulation-content" v-html="regulation.content"></div>
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
      regulation: {}
    }
  },
  mounted: function () {
    this.fetchRegulation()
  },
  methods: {
    fetchRegulation: function () {
      this.$ajax('/msg/detail', { data: { cid: 7 } }).then(res => { // cid为7表示 考级简章 的数据
        if (res && !res.error) { // 获取成功
          this.regulation = res.data
        }
      }).catch(err => {
        console.log('err', err)
      })
    }
  }
}
</script>

<style scoped>
.p-regulation-detail{
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
.regulation-content /deep/ p,.regulation-content /deep/ div{
  line-height: 24px !important;
}
.regulation-content /deep/ img{
  max-width: 100% !important;
}
</style>
