<template>
  <div class="p-miniapp-message-detail">
    <div class="message-detail-body">
      <div class="message-content" v-html="message.content"></div>
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
      docTitleText: {
        1: '成绩查询通知详情',
        2: '准考证领取通知详情',
        3: '考场查询通知详情',
        4: '考试报名通知详情',
        5: '大赛通知详情',
        6: '系统通知详情',
        7: '审核未通过详情',
        8: '缴费通知详情',
        9: '缴费成功通知详情',
        10: '缺考顺延通知详情'
      },
      message: {}
    }
  },
  mounted: function () {
    this.fetchMessage()
  },
  methods: {
    fetchMessage: function () {
      this.$ajax('/inform/detail', { data: { id: this.$route.query.id } }).then(res => {
        if (res && res.data && !res.error) { // 获取成功
          if (res.data.type) {
            let docTitle = this.docTitleText[res.data.type.toString()]
            document.title = docTitle
          }
          this.message = res.data
        }
      }).catch(err => {
        console.log('err', err)
      })
    }
  }
}
</script>

<style scoped>
.p-miniapp-message-detail{
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
.message-content{
  white-space: pre-wrap;
  word-break: break-all;
  padding: 20px 0 10px;
}
.message-content /deep/ p,.message-content /deep/ div{
  line-height: 175% !important;
  word-break: break-word;
  white-space: pre-wrap;
}
.message-content /deep/ img{
  max-width: 100% !important;
  display: block;
  margin: 0 auto;
}
</style>
