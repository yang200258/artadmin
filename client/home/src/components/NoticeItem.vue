<template>
  <div class="c-notice-item">
    <div class="title">{{notice.title}}</div>
    <div class="notice-body clearfix">
      <div v-if="notice.table && notice.table[0]" class="notice-box">
        <div class="notice-row clearfix" v-for="(row, idx) in notice.table" :key="idx">
          <p class="notice-unit fl" v-for="(unit, uidx) in row" :key="uidx">{{unit.name}}：{{unit.value}}</p>
        </div>
      </div>
    </div>
    <div v-if="notice.entry && notice.entry.query_apply" class="button cursor-pointer fr" :class="{disabled: notice.entryDisabled && notice.entryDisabled.query_apply}" @click.stop="applyClick(notice.id)">{{(notice.entryDisabled && notice.entryDisabled.query_apply) ? (beforeDeadline ? '未开始报名' : '截止报名') : '立即报名'}}</div>
  </div>
</template>

<script>
export default {
  props: {
    notice: {
      type: Object,
      required: true
    }
  },
  computed: {
    beforeDeadline () {
      let now = new Date().getTime()
      return now < this.notice.applyStartTime
    }
  },
  methods: {
    applyClick: function (id) {
      let disabled = this.notice.entryDisabled && this.notice.entryDisabled.query_apply
      if (disabled) {
        return false
      }
      this.$router.push({ path: '/enroll/apply?id=' + id })
    }
  }
}
</script>

<style scoped>
.c-notice-item{
  background: #fff;
  padding: 19px 15px 25px 36px;
  position: relative;
}
.title{
  font-size: 18px;
  line-height: 26px;
  color: #333333;
  font-weight: bold;
}
.notice-box{
  width: 810px;
}
.notice-row{
  font-size: 14px;
  line-height: 20px;
  color: #787A7D;
  margin-top: 25px;
}
.notice-unit{
  width: 50%;
}
.button{
  width: 134px;
  height: 46px;
  line-height: 46px;
  color: #8B572A;
  background: #FAE5CB;
  text-align: center;
  border-radius: 3px;
  position: absolute;
  right: 15px;
  bottom: 25px;
}
.button.disabled{
  background: #979797;
  color: #fff;
  cursor: default;
}
</style>
