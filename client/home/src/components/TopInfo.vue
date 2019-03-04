<template>
  <div class="c-top-info clearfix" :style="{height: height + 'px'}">
    <img class="logo fl" alt="中国音乐学院考级委员会-海南考区" title="中国音乐学院考级委员会-海南考区" :style="{marginTop: ((height - 55) / 2) + 'px'}" src="../assets/image/name_logo.png" />
    <div class="login fr clearfix" :style="{marginTop: ((height - 40) / 2) + 'px'}">
      <i class="ykfont yk-login fl"></i>
      <div class="center-text fl">
        <p><span class="login-btn" :class="{'cursor-pointer': !storeUD.username}" @click.stop="loginClick">{{storeUD.username || '登录'}}</span><span v-if="storeUD.username" class="logout-btn cursor-pointer" @click.stop="logoutClick">退出</span></p>
        <p v-if="!storeUD.username" style="color:#666">login</p>
      </div>
    </div>
    <div class="about-us fr clearfix" :style="{marginTop: ((height - 40) / 2) + 'px'}">
      <i class="ykfont yk-wechat fl"></i>
      <div class="center-text fl">
        <p class="cursor-pointer" @click.stop="attendClick">关注我们</p>
        <p style="color:#666">attention</p>
      </div>
      <div class="public-account-box">
        <div class="public-account-qrcode" :style="{backgroundImage: 'url(' + accountImage + ')'}"></div>
        <div class="public-account-tip">微信关注我们<br />获取最新资讯</div>
      </div>
    </div>
  </div>
</template>

<script>
import utils from '../lib/utils'
import { mapState, mapMutations } from 'vuex'
const accountImage = require('../assets/image/account_code.jpg')

export default {
  props: {
    height: {
      type: Number,
      default: 80
    }
  },
  data () {
    return {
      accountImage: accountImage
    }
  },
  computed: {
    ...mapState('user', ['storeUD'])
  },
  methods: {
    ...mapMutations('user', ['userLogout']),
    loginClick: function () {
      console.log('loginClick')
      if (this.storeUD.username) { // 已登录则不需要再跳转登录页面
        return false
      }
      window.localStorage.loginBack = this.$route.path
      this.$router.replace({ path: '/login' })
      return false
    },
    logoutClick: function () {
      console.log('logoutClick')
      this.$ajax('/logout').then(res => {
        if (res && (res.error === 0 || res.error === '0')) { // 退出成功
          this.$toast(res.msg || '退出成功')
          this.userLogout()
          if (this.$route.meta.requiredLogin) {
            utils.goLogin()
          }
        }
      }).catch(err => {
        console.log('err', err)
      })
    },
    attendClick: function () {
      console.log('attendClick')
    }
  }
}
</script>

<style scoped>
.c-top-info{
  overflow: visible;
}
.logo{
  display: block;
  width: 514px;
  height: 55px;
  margin-top: 12px;
}
.login,.about-us{
  margin-top: 20px;
  margin-left: 26px;
  overflow: visible;
  position: relative;
}
.logout-btn{
  margin-left: 20px;
}
.yk-login,.yk-wechat{
  font-size: 14px;
  line-height: 20px;
  color: #666;
  margin-right: 6px;
}
.center-text{
  text-align: center;
  font-size: 14px;
  line-height: 20px
}
.public-account-box{
  overflow: hidden;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 0 5px 3px rgba(0,0,0,0.4);
  position: absolute;
  left: -160px;
  top: 0;
  z-index: 9;
  transition: all 500ms ease-in-out;
  transform: translateY(-200%);
  opacity: 0;
  visibility: hidden;
}
.about-us:hover .public-account-box{
  visibility: visible;
  transform: translateY(0);
  opacity: 1;
}
.public-account-qrcode{
  width: 120px;
  height: 120px;
  background-repeat: no-repeat;
  background-size: contain;
  background-position: cneter;
  margin: 8px 12px;
}
.public-account-tip{
  width: 144px;
  font-size: 16px;
  line-height: 20px;
  text-align: center;
  padding-bottom: 18px;
  color: #777;
}
</style>
