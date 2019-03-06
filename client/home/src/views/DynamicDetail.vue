<template>
  <div class="p-dynamic-detail">
    <top-info />
    <top-bar />
    <sub-nav :navs="subNavs" />
    <div class="dynamic-detail-body">
      <div class="header">
        <div class="title">{{dynamic.title}}</div>
        <div v-if="dynamic.create_at" class="time">发布日期：{{dynamic.create_at}}</div>
      </div>
      <div class="dynamic-content" v-html="dynamic.content"></div>
    </div>
    <bottom-info />
  </div>
</template>

<script>
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
      subNavs: [{ name: 'Home', label: '海南考区', link: '/' }, { name: 'DynamicDetail', label: '动态详情', link: '/dynamicdetail' }],
      dynamic: {}
    }
  },
  components: { TopInfo, TopBar, BottomInfo, SubNav },
  mounted: function () {
    let cateName = this.$route.query.catename
    if (cateName) {
      this.subNavs[this.subNavs.length - 1].label = cateName
    }
    this.fetchDynamc()
  },
  methods: {
    fetchDynamc: function () {
      this.$ajax('/msg/detail', { data: { id: this.$route.query.id } }).then(res => {
        if (res && !res.error) { // 获取成功
          this.dynamic = res.data
          if (res.data.category_name) { // 设置label
            this.subNavs[this.subNavs.length - 1].label = res.data.category_name
          }
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
  overflow-x: visible;
}
.dynamic-detail-body{
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
.dynamic-content{
  padding: 30px 50px;
}
.dynamic-content /deep/ div, .dynamic-content /deep/ p {
  line-height: 175%;
  word-break: break-word;
  white-space: pre-wrap;
}
.dynamic-content /deep/ p{
  margin-bottom: 10px;
}
.dynamic-content /deep/ img{
  max-width: 100%;
  display: block;
  margin: 5px auto;
}
</style>
