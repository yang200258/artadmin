<template>
  <div class="p-query-score">
    <div class="filters-wrapper">
      <div class="input-box clearfix">
        <i class="ykfont yk-user fl"></i>
        <input class="user-input fr" v-model="filters.name" placeholder="姓名" />
      </div>
      <div class="input-box clearfix">
        <i class="ykfont yk-card fl"></i>
        <input class="card-input fr" v-model="filters.cardNumber" placeholder="身份证号" />
      </div>
      <div class="submit-btn cursor-pointer" @click.stop="requestList">提交</div>
    </div>
    <div v-show="loading" class="loading-tip">正在查询...</div>
    <div v-if="loaded" class="result-wrapper">
      <div class="result-head">考级信息</div>
      <div v-if="result && result[0]" class="result-box">
        <div v-for="enroll in result" :key="enroll.id" class="result-item clearfix">
          <div class="name fl">{{enroll.domain}}{{enroll.class}}级</div>
          <div class="more fr cursor-pointer" @click.stop="seeMore(enroll)">查看</div>
        </div>
      </div>
      <div v-else class="empty-tip">暂无信息~</div>
    </div>
  </div>
</template>

<script>

export default {
  data () {
    return {
      majors: [],
      levels: [],
      filters: {
        name: '',
        cardNumber: '',
        major: '全部',
        level: '全部'
      },
      loading: false,
      loaded: false,
      result: []
    }
  },
  methods: {
    requestList: function () {
      console.log('filters', this.filters)
      let { name, cardNumber } = this.filters
      if (!name) {
        this.$toast('请输入姓名')
        return false
      } else if (!cardNumber) {
        this.$toast('请输入身份证号')
        return false
      }
      let rData = {
        name: name,
        id_card: cardNumber
      }
      this.loading = true
      this.$ajax('/search/grade', { data: rData }).then(res => {
        this.loading = false
        this.loaded = true
        console.log('/search/grade', res)
        if (res && res.error.toString() === '0') { // 获取数据成功
          if (res.data) {
            this.result = res.data
            if (!res.data[0]) {
              this.$toast('未查询到该考生成绩信息')
            }
          } else {
            this.$toast('未查询到该考生信息')
          }
        }
        if (res && res.error && res.error.toString() !== '0') {
          if (res.msg) {
            this.$toast(res.msg)
          }
        }
      }).catch(err => {
        this.loading = false
        console.log('获取成绩失败', err)
      })
    },
    seeMore: function (enroll) {
      this.$router.push({ name: 'ScoreResult', params: { enroll } })
    }
  }
}
</script>

<style scoped>
.filters-wrapper{
  background: #fff;
  padding: 20px 0 24px;
}
.input-box{
  padding: 14px 0;
  width: 350px;
  margin: 0 auto;
}
.yk-user,.yk-card{
  display: block;
  width: 40px;
  height: 40px;
  background: #795C41;
  color: #fff;
  font-size: 32px;
  line-height: 40px;
  text-align: center;
  border-radius: 50%;
}
.user-input,.card-input{
  width: 298px;
  height: 38px;
  border: 1px solid #795C41;
  text-indent: 14px;
  color: #141619;
}
.submit-btn{
  width: 200px;
  height: 40px;
  line-height: 40px;
  font-size: 18px;
  color: #795C41;
  background: #FAE5CB;
  text-align: center;
  margin: 86px auto 0;
}
.loading-tip{
  margin-top: 25px;
  text-align: center;
}
.result-wrapper{
  padding-bottom: 100px;
  margin-top: 25px;
  background: #fff;
  overflow: hidden;
}
.result-head{
  width: 160px;
  height: 50px;
  margin: 15px auto 0;
  background: url('../assets/image/button_actived.png') no-repeat;
  background-size: 100% 100%;
  background-position: center;
  line-height: 50px;
  font-size: 18px;
  color: #76593D;
  text-align: center;
}
.result-box{
  width: 500px;
  padding: 32px 0;
  margin: 0 auto;
}
.result-item{
  font-size: 18px;
  color: #000;
  line-height: 56px;
  border-bottom: 1px solid #979797;
}
.more{
  color: #76593D;
}
</style>
