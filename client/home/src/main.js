import Vue from 'vue'
import ajax from './lib/ajax'
import toast from './components/toast'
import App from './App.vue'
import router from './router'
import './plugins/element.js'

Vue.config.productionTip = false

Vue.prototype.$ajax = ajax
Vue.prototype.$toast = toast

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
