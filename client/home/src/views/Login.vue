<template>
  <div class="p-login">
    <div class="login-body">
      <div class="login-box clearfix">
        <div class="left-box fl">
          <p class="sub-title">Social Art Grade Examination of China Conservatory of Music</p>
          <p class="title">中国音乐学院考级委员会 海南考区</p>
        </div>
        <div class="right-box fr">
          <div class="login-content">
            <div class="login-tab clearfix">
              <div class="login-tab-item fl cursor-pointer" :class="{actived: seleced === 0}" @click.stop="changeTab(0)">考生</div>
              <div class="login-tab-item fl cursor-pointer" :class="{actived: seleced === 1}" @click.stop="changeTab(1)">老师</div>
              <div class="login-tab-item fl cursor-pointer" :class="{actived: seleced === 2}" @click.stop="changeTab(2)">机构</div>
            </div>
            <div class="login-form">
              <div v-show="seleced === 0" class="std">
                <div class="weixin-login-box">
                  <i class="ykfont yk-weixin"></i>
                </div>
                <div class="weixin-login-btn cursor-pointer" @click="login">点此使用微信登录</div>
              </div>
              <div v-show="seleced === 1" class="tec">
                <div class="input-box clearfix">
                  <span class="input-title fl">登录账号：</span>
                  <input class="input-content fr" placeholder="请输入账号" type="text" v-model="form1.username" />
                </div>
                <div class="input-box clearfix">
                  <span class="input-title fl">登录密码：</span>
                  <input class="input-content fr" placeholder="请输入密码" type="password" :disabled="!form1.username" v-model="form1.password" />
                </div>
                <div class="tip clearfix" :class="{show: tip1}"><i class="ykfont yk-error fl"></i><span class="tip-text fl">{{tip1}}</span></div>
                <div class="tec-login-btn cursor-pointer" @click="login">登录</div>
              </div>
              <div v-show="seleced === 2" class="ins">
                <div class="input-box clearfix">
                  <span class="input-title fl">登录账号：</span>
                  <input class="input-content fr" placeholder="请输入账号" type="text" v-model="form2.username" />
                </div>
                <div class="input-box clearfix">
                  <span class="input-title fl">登录密码：</span>
                  <input class="input-content fr" placeholder="请输入密码" type="password" :disabled="!form2.username" v-model="form2.password" />
                </div>
                <div class="tip clearfix" :class="{show: tip2}"><i class="ykfont yk-error fl"></i><span class="tip-text fl">{{tip2}}</span></div>
                <div class="ins-login-btn cursor-pointer" @click="login">登录</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showWxLogin" class="wechat-login-mask">
      <div class="wechat-login-mask-box">
        <!-- <div class="mask-header">微信登录</div> -->
        <div class="wechat-qr" id="wxcode"></div>
        <!-- <div class="mask-tip">请使用微信扫描二维码登录<br />“中国音乐学院考级委员会海南考区报名”</div> -->
      </div>
      <i class="ykfont yk-close cursor-pointer" @click.stop="showWxLogin = false"></i>
    </div>
  </div>
</template>

<script>
import '../lib/WxLogin'
import { mapMutations } from 'vuex'

export default {
  data () {
    return {
      seleced: 1,
      showWxLogin: false,
      form1: {
        username: '',
        password: '',
        type: '1'
      },
      tip1: '',
      form2: {
        username: '',
        password: '',
        tyep: '2'
      },
      tip2: '',
      tokenTimer: null,
      wxLoginObj: null
    }
  },
  watch: {
    'wxLoginObj' (p1, p2) {
      console.log('watchwxLoginObj', p1, p2)
    }
  },
  beforeDestroy () {
    if (this.tokenTimer) {
      clearTimeout(this.tokenTimer)
    }
  },
  methods: {
    ...mapMutations('user', ['userLogin']),
    changeTab: function (idx) {
      if (this.seleced === parseInt(idx)) {
        return false
      }
      this.seleced = parseInt(idx)
    },
    getWxLoginToken: function (state) {
      if (this.tokenTimer) {
        clearTimeout(this.tokenTimer)
      }
      this.tokenTimer = setTimeout(() => {
        let rData = {
          state
        }
        this.$ajax('/login/get-token', { data: rData, dontToast: true }).then(res => {
          if (res && res.data && res.data.token && (res.error === 0 || res.error === '0')) { // 成功获取token
            if (this.tokenTimer) {
              clearTimeout(this.tokenTimer)
            }
            let obj = {}
            obj.token = res.data.token
            obj.username = res.data.username
            obj.userType = res.data.type
            this.userLogin(obj)
            let loginBack = window.localStorage.loginBack || '/'
            this.$router.replace({ path: loginBack })
          } else {
            this.getWxLoginToken(state)
          }
        }).catch(() => {
          this.getWxLoginToken(state)
        })
      }, 2500)
    },
    login: function () {
      let rData = {}
      if (this.seleced.toString() === '0') { // 考生登录
        this.$ajax('/login/get-state').then(res => {
          if (res && (res.error === 0 || res.error === '0')) { // 成功获取state
            let { state } = res.data
            this.showWxLogin = true
            this.$nextTick(() => {
              this.wxLoginObj = new window.WxLogin({
                self_redirect: true,
                id: 'wxcode',
                appid: 'wxe2f2c66f76dbd611',
                scope: 'snsapi_login',
                redirect_uri: 'https%3A%2F%2Fwww.hnyskj.net%2Flogin%2Fwx-call-back',
                state: state,
                style: 'white',
                href: ''
              })
              this.getWxLoginToken(state)
            })
          } else {
            this.$toast(res.msg || '微信暂时无法登录')
          }
        }).catch(err => {
          if (!err.msg) {
            this.$toast('微信暂时无法登录')
          }
        })
        return false
      } else if (this.seleced.toString() === '1') { // 老师登录
        rData = this.form1
      } else if (this.seleced.toString() === '2') { // 机构登录
        rData = this.form2
      }
      if (!rData.username) {
        this['tip' + this.seleced.toString()] = '请输入账号'
        return false
      } else if (!rData.password) {
        this['tip' + this.seleced.toString()] = '请输入密码'
        return false
      } else {
        this['tip' + this.seleced.toString()] = ''
      }
      this.$ajax('/login', { data: rData }).then(res => {
        if (res && !res.error) { // 登录成功
          let obj = {}
          obj.token = res.data.token
          obj.username = res.data.username
          obj.userType = res.data.type
          this.userLogin(obj)
          let loginBack = window.localStorage.loginBack || '/'
          this.$router.replace({ path: loginBack })
        } else if (res && res.error && res.msg) {
          this.$toast(res.msg)
        }
      }).catch(err => {
        console.log('err', err)
      })
    }
  },
  beforeRouteLeave (to, from, next) {
    let loginBack = window.localStorage.loginBack || '/'
    console.log('to', to, 'loginBack', loginBack)
    if (to.path === loginBack) {
      next()
    } else {
      next({ path: loginBack, replace: true })
    }
  }
}
</script>

<style scoped>
.p-login{
  position: fixed;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  z-index: 0;
  background-color: #FDF2D8;
}
.login-body{
  position: fixed;
  width: 1440px;
  height: 640px;
  left: 50%;
  top: 50%;
  margin-left: -720px;
  margin-top: -320px;
  background: url('../assets/image/home_banner.jpg') no-repeat;
  background-position: center;
  background-color: #FDF2D8;
  background-size: 1200px 490px;
  z-index: 1;
}
.login-box{
  width: 1036px;
  height: 520px;
  position: relative;
  left: 202px;
  top: 60px;
}
.left-box,.right-box{
  width: 50%;
  height: 100%;
}
.left-box{
  background: rgba(185,104,31,0.6);
  text-align: center;
  color: #fff;
  font-weight: bold;
}
.title{
  font-size: 36px;
  line-height: 50px;
  padding: 70px 60px 0;
}
.sub-title{
  font-size: 20px;
  line-height: 28px;
  padding: 70px 60px 0;
}
.right-box{
  background: rgba(255,255,255,0.9);
}
.login-content{
  margin: 54px 33px;
}
.login-tab{
  border-bottom: 1px solid #795C41;
}
.login-tab-item{
  font-size: 18px;
  line-height: 40px;
  color: #795C41;
  width: 64px;
  margin-left: 22px;
  text-align: center;
}
.login-tab-item:first-child{
  margin-left: 0;
}
.login-tab-item:hover{
  font-weight: bold;
}
.login-tab-item.actived{
  font-weight: bold;
  background: #F2E0CA;
}
.std,.tec,.ins{
  padding-top: 15px;
}
.weixin-login-box{
  height: 250px;
  position: relative;
}
.yk-weixin{
  display: block;
  font-size: 160px;
  line-height: 160px;
  color: #00C800;
  position: absolute;
  left: 50%;
  top: 50%;
  margin-left: -80px;
  margin-top: -80px;
}
.weixin-login-btn{
  width: 215px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  font-size: 18px;
  color: #795C41;
  background: #F2E0CA;
  border-radius: 5px;
  margin: 0 auto;
}
.input-box{
  padding: 22px 0;
  font-size: 16px;
  line-height: 44px;
}
.input-title{
  padding-top: 1px;
  color: #795C41;
}
.input-content{
  width: 365px;
  height: 44px;
  line-height: 44px;
  border: 1px solid rgba(151,151,151,0.6);
  text-indent: 20px;
  border-radius: 5px;
  outline: none;
  padding: 0;
}
.input-content:disabled{
  background: #D8D8D8;
  border: 1px solid #D8D8D8;
}
.tip{
  margin-left: 85px;
  opacity: 0;
  visibility: hidden;
}
.tip.show{
  opacity: 1;
  visibility: visible;
}
.yk-error{
  font-size: 20px;
  line-height: 24px;
  color: #D0021B;
}
.tip-text{
  font-size: 16px;
  line-height: 24px;
  margin-left: 10px;
  color: #D0021B;
}
.tec-login-btn,.ins-login-btn{
  width: 180px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  font-size: 18px;
  color: #795C41;
  background: #F2E0CA;
  border-radius: 5px;
  margin: 46px auto 0;
}
.wechat-login-mask{
  position: fixed;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  z-index: 2;
  background-color: rgba(51,51,51,0.8);
}
.wechat-login-mask-box{
  position: absolute;
  width: 100%;
  height: 478px;
  left: 0;
  top: 50%;
  margin-top: -239px;
}
.mask-header{
  font-size: 24px;
  line-height: 72px;
  color: #fff;
  font-weight: bold;
  text-align: center;
}
.wechat-qr{
  display: block;
  width: 290px;
  height: 290px;
  padding: 0;
  margin: 0 auto;
  /* background: #fff; */
}
.mask-tip{
  font-size: 18px;
  line-height: 26px;
  color: #fff;
  padding: 8px 0;
  width: 410px;
  background: rgba(35,35,35,0.8);
  border-radius: 34px;
  text-align: center;
  margin: 46px auto 0;
}
.yk-close{
  display: block;
  position: absolute;
  font-size: 44px;
  line-height: 44px;
  top: 30px;
  right: 40px;
  color: #fff;
}
</style>
