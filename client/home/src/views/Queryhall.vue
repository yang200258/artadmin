<template>
  <div class="p-query-hall">
    <div class="filters-wrapper">
      <div class="input-box clearfix">
        <i class="ykfont yk-user fl"></i>
        <input class="user-input fr" v-model="filters.name" placeholder="姓名" />
      </div>
      <div class="input-box clearfix">
        <i class="ykfont yk-card fl"></i>
        <input class="card-input fr" v-model="filters.cardNumber" placeholder="身份证号" />
      </div>
      <div class="select-box clearfix">
        <el-select v-model="filters.major" placeholder="请选择专业" class="select-picker fl" @change="value => pickerChange('major', value)">
          <el-option
            v-for="item in majors"
            :key="item.value"
            :label="item.value"
            :value="item.value">
          </el-option>
        </el-select>
        <el-select v-model="filters.level" placeholder="请选择级别" class="select-picker fr">
          <el-option
            v-for="item in levels"
            :key="item"
            :label="item"
            :value="item">
          </el-option>
        </el-select>
      </div>
      <div class="submit-btn cursor-pointer" @click.stop="requestList">提交</div>
    </div>
    <div v-show="loading" class="loading-tip">正在查询...</div>
    <div v-if="resultUrl" class="result-wrapper">
      <img class="result-image" :src="resultUrl" />
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
      resultUrl: ''
    }
  },
  actived () {
    this.getOptions()
  },
  methods: {
    requestList: function () {
      console.log('filters', this.filters)
      this.loading = true
      setTimeout(() => {
        this.loading = false
        this.resultUrl = 'http://img2.imgtn.bdimg.com/it/u=2682247153,4259400185&fm=26&gp=0.jpg'
      }, 500)
    },
    getOptions: function () {
      this.$ajax('/option', { data: { token: window.localStorage.token || '' } }).then(res => {
        if (!res.error && res.data) { // 获取信息成功
          let { major } = res.data
          let majors = []
          for (let mj in major) {
            major[mj].unshift('全部')
            majors.push({ value: mj, levels: major[mj] })
          }
          majors.unshift({ value: '全部', levels: ['全部'] })
          this.majors = majors
        }
      })
    },
    pickerChange: function (type, value) {
      if (type === 'major') {
        this.levels = this.majors.filter(item => item.value === value)[0].levels
        if (this.levels.indexOf(this.filters.level) === -1) { // 已选中的等级不存在，重置为全部
          this.filters.level = '全部'
        }
      }
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
.select-picker /deep/ .el-input__inner{
  border: 1px solid #795C41;
  padding-left: 14px;
  border-radius: 0;
  width: 200px;
  color: #141619;
}
.select-box{
  width: 500px;
  padding: 42px 0 49px;
  margin: 0 auto;
}
.submit-btn{
  width: 200px;
  height: 40px;
  line-height: 40px;
  font-size: 18px;
  color: #795C41;
  background: #FAE5CB;
  text-align: center;
  margin: 0 auto;
}
.loading-tip{
  margin-top: 25px;
  text-align: center;
}
.result-wrapper{
  padding: 25px 0;
  margin-top: 25px;
  background: #fff;
}
.result-image{
  display: block;
  width: 970px;
  margin: 0 auto;
  border: 1px solid #979797;
}
</style>
