import Vue from 'vue'
import ajax from './lib/ajax'
import toast from './components/toast'
import App from './App.vue'
import router from './router'
import './plugins/element.js'
import store from './store'

Vue.config.productionTip = false

Vue.prototype.$ajax = ajax
Vue.prototype.$toast = toast

let isMiniappPage = false
if (window.location.href.indexOf('/miniapp') !== -1) { // 小程序里调用store.commit会出错，导致页面不渲染，在小程序页面时不调用store.commit
  isMiniappPage = true
}
if (!isMiniappPage) {
  let localUserInfo = {}
  localUserInfo.token = window.localStorage.token
  localUserInfo.username = window.localStorage.username
  localUserInfo.userType = window.localStorage.userType
  store.commit('user/userLogin', localUserInfo)
}

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
