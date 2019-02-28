<template>
  <div class="p-enroll">
    <top-info />
    <top-bar />
    <sub-nav :navs="subNavs" />
    <div class="enroll-body">
      <div class="enroll-body-content clearfix">
        <div class="side-bar fl">
          <div class="side-bar-item cursor-pointer" :class="{actived: $route.path.indexOf('/enroll/notice') !== -1}" @click.stop="sidebarClick('1')">考级通知</div>
          <div class="side-bar-item cursor-pointer" :class="{actived: $route.path.indexOf('/enroll/manage') !== -1}" @click.stop="sidebarClick('2')">报名管理</div>
          <div class="side-bar-item cursor-pointer" :class="{actived: $route.path.indexOf('/enroll/queryhall') !== -1}" @click.stop="sidebarClick('3')">考场查询</div>
          <div class="side-bar-item cursor-pointer" :class="{actived: $route.path.indexOf('/enroll/queryscore') !== -1}" @click.stop="sidebarClick('4')">成绩查询</div>
          <div class="side-bar-item cursor-pointer" :class="{actived: $route.path.indexOf('/regulation') !== -1}" @click.stop="sidebarClick('5')">考级简章</div>
        </div>
        <div class="side-body fr">
          <keep-alive>
            <router-view />
          </keep-alive>
        </div>
      </div>
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
      subNavs: [{ name: 'Home', label: '海南考区', link: '/' }, { name: 'Enroll', label: '考级报名', link: '/enroll' }],
      enrollNotice: [
        {
          id: 1,
          title: '关于中国音乐学院本院在2019年寒假的考级通知',
          table: [
            [
              { name: '考试编号', value: '20190119001' },
              { name: '报名起止时间', value: '2018年12月10日 至 2018年12月20日' }
            ],
            [
              { name: '考试名称', value: '中国音乐学院本院在2019年寒假考级' },
              { name: '考试起止时间', value: '2019年01月19日 至 2019年01月25日' }
            ]
          ],
          entry: {
            query_enroll: false,
            query_pay: false,
            query_score: false,
            query_hall: false,
            query_apply: true
          }
        }
      ]
    }
  },
  components: { TopInfo, TopBar, BottomInfo, SubNav },
  methods: {
    sidebarClick: function (type) {
      if (type.toString() === '1') { // 点击考级通知
        this.$router.replace({ path: '/enroll/notice' })
      } else if (type.toString() === '2') { // 点击报名管理
        this.$router.replace({ path: '/enroll/manage' })
      } else if (type.toString() === '3') { // 点击考场查询
        this.$router.replace({ path: '/enroll/queryhall' })
      } else if (type.toString() === '4') { // 点击成绩管理
        this.$router.replace({ path: '/enroll/queryscore' })
      } else if (type.toString() === '5') { // 点击考级简章
        this.$router.push({ path: '/regulation' })
      }
    }
  }
}
</script>

<style scoped>
.p-enroll{
  overflow-x: visible;
}
.enroll-body{
  width: 9200px;
  min-height: 1000px;
  padding-top: 30px;
  padding-bottom: 20px;
  background: #EEEEEE;
  margin-left: -4000px;
}
.enroll-body-content{
  width: 1200px;
  margin: 0 auto;
}
.side-bar{
  width: 160px;
  background: #fff;
  text-align: center;
}
.side-bar-item{
  font-size: 18px;
  line-height: 50px;
  color: #76593D;
}
.side-bar-item:hover{
  font-weight: bold;
}
.side-bar-item.actived{
  font-weight: bold;
  background: url('../assets/image/button_actived.png') no-repeat;
  background-size: 100% 100%;
  background-position: center;
  background-color: #FAE5CB;
  transition: background-image 0.4s;
}
.side-body{
  width: 1010px;
}
</style>
