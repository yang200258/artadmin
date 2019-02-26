<template>
  <div class="p-enroll-manage">
    <div v-if="userRole.toString() === roles.teacher || userRole.toString() === roles.institution" class="filter-wrapper clearfix">
      <div class="filter-box fl clearfix">
        <span class="filter-name fl">考生姓名：</span>
        <input class="filter-input fl" v-model="filters.name" placeholder="考生姓名" />
      </div>
      <div class="filter-box fl clearfix">
        <span class="filter-name fl">报考专业：</span>
        <el-select v-model="filters.major" placeholder="报考专业" class="select-picker" @change="value => pickerChange('major', value)">
          <el-option
            v-for="item in majors"
            :key="item.value"
            :label="item.value"
            :value="item.value">
          </el-option>
        </el-select>
      </div>
      <div class="filter-box fl clearfix">
        <span class="filter-name fl">报考级别：</span>
        <el-select v-model="filters.level" placeholder="报考级别" class="select-picker">
          <el-option
            v-for="item in levels"
            :key="item"
            :label="item"
            :value="item">
          </el-option>
        </el-select>
      </div>
      <div class="filter-box fl clearfix">
        <span class="filter-name fl">报名日期：</span>
        <el-date-picker
          class="range-picker"
          v-model="filters.range"
          type="daterange"
          value-format="yyyy-MM-dd"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期">
        </el-date-picker>
      </div>
      <div class="filter-box fl clearfix">
        <div class="filter-btn cursor-pointer fl" @click.stop="filter">筛选</div>
      </div>
      <div class="filter-box fl clearfix">
        <div class="reset-btn cursor-pointer fl" @click.stop="filter(true)">重置</div>
      </div>
    </div>
    <div class="list-table">
      <el-table
        :data="listTable"
        class="table-box"
        header-row-class-name="table-head"
        style="width:100%">
        <el-table-column prop="number" label="考试编号" width="91"></el-table-column>
        <el-table-column prop="examName" label="考试名称" width="91"></el-table-column>
        <el-table-column prop="orderNo" label="报名单号" width="91"></el-table-column>
        <el-table-column prop="name" label="考生姓名" width="91"></el-table-column>
        <el-table-column prop="gender" label="考生性别" width="91">
          <template slot-scope="scope">
            {{genderText[scope.row.gender]}}
          </template>
        </el-table-column>
        <el-table-column prop="cardnumber" label="证件号码" width="91"></el-table-column>
        <el-table-column prop="major" label="报考专业" width="91"></el-table-column>
        <el-table-column prop="level" label="报考级别" width="91"></el-table-column>
        <el-table-column prop="time" label="报名时间" width="91"></el-table-column>
        <el-table-column prop="status" label="当前进度" width="91">
          <template slot-scope="scope">
            {{statusText[scope.row.status]}}
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
           <span class="cursor-pointer" style="color:#795C41" @click.stop="moreDetail(scope.row.id)">查看详情</span>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import globalConstant from '../lib/globalConstant'

export default {
  data () {
    return {
      roles: globalConstant.roles,
      statusText: globalConstant.statusText,
      genderText: globalConstant.genderText,
      userRole: '0',
      majors: [],
      levels: [],
      filters: {
        name: '',
        major: '全部',
        level: '全部',
        range: ''
      },
      listTable: [
        {
          id: '22',
          number: '123123',
          examName: '考试1',
          orderNo: '单号1',
          name: '张三',
          gender: '2',
          cardnumber: '142934782947234234',
          major: '钢琴',
          level: '九级',
          time: '2010.10.10 16:16',
          status: '1'
        }
      ]
    }
  },
  activated () {
    this.userRole = window.localStorage.userType ? window.localStorage.userType.toString() : '0'
    this.getOptions()
  },
  methods: {
    test: function () {
      console.log('this.filters', this.filters)
    },
    moreDetail: function (id) {
      console.log('moreDetail', id)
      this.$router.push({ path: '/enroll/detail', query: { id } })
    },
    filter: function (isReset) {
      if (isReset) { // 是否是重置筛选项
        this.filters = {
          name: '',
          major: '全部',
          level: '全部',
          range: ''
        }
      }
    },
    requestList: function () {

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
.filter-wrapper{
  font-size: 16px;
  color: #000;
  padding: 10px 15px;
  background: #fff;
  margin-bottom: 25px;
}
.filter-box{
  margin-right: 50px;
  padding: 10px 0;
}
.filter-name{
  line-height: 40px;
}
.select-picker /deep/ .el-input__inner{
  border: 1px solid #979797;
  padding-left: 14px;
  border-radius: 0;
}
.filter-input{
  width: 118px;
  height: 38px;
  border: 1px solid #979797;
  text-indent: 14px;
}
.range-picker{
  border: 1px solid #979797;
  border-radius: 0;
}
.filter-btn,.reset-btn{
  width: 78px;
  height: 38px;
  line-height: 38px;
  text-align: center;
  border: 1px solid #979797;
  border-radius: 5px;
}
.table-box /deep/ .table-head, .table-box /deep/ th{
  background: #FFF7EC;
  height: 36px;
  line-height: 36px;
}
</style>
