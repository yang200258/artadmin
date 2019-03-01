<template>
  <div class="p-dynamic-detail">
    <div class="dynamic-detail-body">
      <div class="header">
        <div class="title">{{dynamic.title}}</div>
        <!-- <div v-if="dynamic.intro" class="intro">{{dynamic.intro}}</div> -->
      </div>
      <div class="dynamic-content" v-html="dynamic.content"></div>
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
      dynamic: {}
    }
  },
  mounted: function () {
    this.fetchDynamc()
  },
  methods: {
    fetchDynamc: function () {
      this.$ajax('/msg/detail', { data: { id: this.$route.query.id } }).then(res => {
        if (res && !res.error) { // 获取成功
          this.dynamic = res.data
        }
      }).catch(err => {
        console.log('err', err)
      })
    }
  }
}
</script>

<style scoped>
.p-dynamic-detail{
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
.dynamic-content /deep/ p,.dynamic-content /deep/ div{
  line-height: 175% !important;
  word-break: break-word;
  white-space: pre-wrap;
}
.dynamic-content /deep/ img{
  max-width: 100% !important;
  display: block;
  margin: auto;
}
</style>
