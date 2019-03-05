// import store from '@/store'
// import toast from '../../components/toast'

export default {
  namespaced: true,
  state: {
    storeUD: {
      isLogin: false,
      token: '',
      username: '',
      userType: ''
    }
  },
  mutations: {
    userLogin (state, payload) {
      let { token, username, userType } = payload
      let obj = {}
      obj.isLogin = Boolean(token)
      obj.username = username
      obj.userType = userType ? userType.toString() : ''
      state.storeUD = obj
      window.localStorage.token = token
      window.localStorage.username = username
      window.localStorage.userType = userType ? userType.toString() : ''
    },
    userLogout (state, payload) {
      let obj = {}
      obj.isLogin = false
      obj.token = ''
      obj.username = ''
      obj.userType = ''
      state.storeUD = obj
      window.localStorage.token = ''
      window.localStorage.username = ''
      window.localStorage.userType = ''
    }
  }
}
