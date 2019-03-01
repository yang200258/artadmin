<template>
  <div class="p-regulation">
    <top-info />
    <top-bar />
    <sub-nav :navs="subNavs" />
    <div class="regulation-detail-body">
      <div class="header">
        <div class="title">{{regulation.title}}</div>
        <div v-if="regulation.create_at" class="time">发布日期：{{regulation.create_at}}</div>
      </div>
      <div class="regulation-content" v-html="regulation.content"></div>
    </div>
    <bottom-info />
  </div>
</template>

<script>
// 考级简章
// @ is an alias to /src
import TopInfo from '../components/TopInfo'
import TopBar from '../components/TopBar'
import BottomInfo from '../components/BottomInfo'
import SubNav from '../components/SubNav'
import mixin from '../lib/mixins'

export default {
  mixins: [mixin],
  data () {
    return {
      subNavs: [{ name: 'Home', label: '海南考区', link: '/' }, { name: 'Regulation', label: '考级简章', link: '/regulation' }],
      regulation: {}
    }
  },
  components: { TopInfo, TopBar, BottomInfo, SubNav },
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
  overflow-x: visible;
}
.regulation-detail-body{
  min-height: 1000px;
  padding-bottom: 20px;
  background: #EFEFEF;
}
.header{
  min-height: 140px;
  margin: 0 50px;
  border-bottom: 1px solid #DDDDDD;
  text-align: center;
}
.title{
  font-size: 32px;
  line-height: 44px;
  color: #000;
  font-weight: bold;
  padding-top: 30px;
}
.time{
  font-size: 14px;
  line-height: 20px;
  color: #9B9B9B;
  padding-top: 15px;
  padding-bottom: 30px;
}
.regulation-content{
  padding: 30px 50px;
}
.regulation-content /deep/ div, .regulation-content /deep/ p {
  line-height: 175%;
  word-break: break-word;
  white-space: pre-wrap;
}
.regulation-content /deep/ p{
  margin-bottom: 10px;
}
.regulation-content /deep/ img{
  max-width: 100%;
  display: block;
  margin: 5px auto;
}
</style>
