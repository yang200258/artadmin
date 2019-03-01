<template>
  <div class="p-us">
    <top-info />
    <top-bar />
    <sub-nav :navs="subNavs" />
    <div class="us-detail-body">
      <div class="header">
        <div class="title">{{aboutUs.title}}</div>
        <div v-if="aboutUs.create_at" class="time">发布日期：{{aboutUs.create_at}}</div>
      </div>
      <div class="us-content" v-html="aboutUs.content"></div>
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

export default {
  data () {
    return {
      subNavs: [{ name: 'Home', label: '海南考区', link: '/' }, { name: 'Us', label: '关于我们', link: '/us' }],
      aboutUs: {}
    }
  },
  components: { TopInfo, TopBar, BottomInfo, SubNav },
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
  overflow-x: visible;
}
.us-detail-body{
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
.us-content{
  padding: 30px 50px;
}
.us-content /deep/ div, .us-content /deep/ p {
  line-height: 175%;
  word-break: break-word;
  white-space: pre-wrap;
}
.us-content /deep/ p{
  margin-bottom: 10px;
}
.us-content /deep/ img{
  max-width: 100%;
  display: block;
  margin: 5px auto;
}
</style>
