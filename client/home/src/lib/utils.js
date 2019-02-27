import router from '../router'
import ajax from './ajax'

export default {
  isPoneAvailable: function (phone) {
    var myreg = /^[1][0-9]{10}$/
    if (!myreg.test(phone)) {
      return false
    } else {
      return true
    }
  },
  setLoginBackParam: function (needPhone) {
    if (router.history.pending) {
      // 解决从App.js过来router.app.$route没有携带路由信息的问题
      // 微信登录可记录所有回跳 普通登陆只能记录smsCode页之外的回跳
      if (router.history.pending.path !== '/h5/smsCode') {
        window.localStorage.loginBackParam = JSON.stringify({
          fullUrl: window.location.origin + router.history.pending.fullPath,
          path: router.history.pending.path,
          query: router.history.pending.query,
          needPhone: needPhone
        })
      }
    } else {
      if (router.app.$route.path !== '/h5/smsCode') {
        window.localStorage.loginBackParam = JSON.stringify({
          fullUrl: window.location.origin + router.app.$route.fullPath,
          path: router.app.$route.path,
          query: router.app.$route.query,
          needPhone: needPhone
        })
      }
    }
  },
  checkLogin: function () {
    if (!(window.localStorage.token && window.localStorage.username)) {
      console.log('router', router)
      window.localStorage.loginBack = router.history.current.path
      router.replace({ path: '/login' })
      return false
    }
    return true
  },
  // 登录后返回登录之前的页面
  loginBack: function () {
    let loginBackParam = JSON.parse(window.localStorage.loginBackParam)
    router.replace(loginBackParam)
  },
  needPhone: function () {
    let loginBackParam = JSON.parse(window.localStorage.loginBackParam)
    return loginBackParam.needPhone
  },
  handleNumberInput: function (event, length, setter) {
    let value = ''
    value = event.target.value
    // if (isNaN(event.data)) {
    //   value = event.currentTarget._value
    // } else {
    //   value = event.target.value
    // }
    // value = value.replace(/[^\d]/g, '')
    if (value.length > length) {
      value = value.slice(0, length)
    }
    setter(value)
    event.target.value = value
  },
  checkReloadWithKeepAliveNew (vm, $route, $oldRoute, routeName, checkQueryKeys, reloadCallback, dontReloadCallback = null) {
    let route = null
    if ($route.name === routeName && $oldRoute.name === routeName) { // 相同路由
      route = $oldRoute // 旧路由
      if (!vm._refresh) {
        vm._refresh = {}
      }
      // 写入初始值
      checkQueryKeys.forEach(checkQueryKey => { // 旧路由初始值
        if (!vm._refresh[checkQueryKey]) {
          vm._refresh[checkQueryKey] = route.query[checkQueryKey]
        }
      })
      route = $route
    } else if ($route.name === routeName) {
      route = $route
    } else if ($oldRoute.name === routeName) {
      route = $oldRoute
    }
    if (route) {
      if (!vm._refresh) {
        vm._refresh = {}
      }
      // 写入初始值
      checkQueryKeys.forEach(checkQueryKey => {
        if (!vm._refresh[checkQueryKey]) {
          vm._refresh[checkQueryKey] = route.query[checkQueryKey]
        }
      })

      // 记录初始刷新时间
      if (!vm._refreshTime) {
        vm._refreshTime = Number(new Date())
      }

      // 判断是否需要reload
      let reload = false
      if ((window.localStorage.userChangeTime &&
        vm._refreshTime < window.localStorage.userChangeTime)) {
        // 发生了登入登出 需要reload
        reload = true
      }
      if (!reload) {
        // 参数发生变化 需要reload
        checkQueryKeys.forEach(checkQueryKey => {
          if ((!vm._refresh[checkQueryKey] && route.query[checkQueryKey]) || (vm._refresh[checkQueryKey] && !route.query[checkQueryKey]) || (vm._refresh[checkQueryKey] && route.query[checkQueryKey] && vm._refresh[checkQueryKey].toString() !== route.query[checkQueryKey].toString())) { // 其中一个不存在，或者值不一样时
            reload = true
          }
        })
        // 主动刷新时 需要reload
        if ($route.name === routeName && $route.params.resetData) {
          reload = true
        }
      }

      if (reload) {
        // 重新记录参数
        checkQueryKeys.forEach(checkQueryKey => {
          vm._refresh[checkQueryKey] = route.query[checkQueryKey]
        })
        // 重新记录时间
        vm._refreshTime = Number(new Date())
        reloadCallback()
      } else {
        if ($route.name === routeName && dontReloadCallback) {
          dontReloadCallback()
        }
      }
    }
  },
  /**
   * 分享打开计数功能 自带next
   * type: 1：群组 2：话题 3：短动态 4：长文 5：活动
   */
  beforeRouteEnterHandleShareOpen: function (to, from, next, type) {
    if (this.beforeRouteEnterHandleShareOpenDontNext(to, from, next, type)) {
      next({
        name: to.name,
        query: to.query,
        params: to.params,
        replace: true
      })
    } else {
      next()
    }
  },
  /**
   * 分享打开计数功能 不带next 返回bool表示是否有路由更新
   * type: 1：群组 2：话题 3：短动态 4：长文 5：活动
   */
  beforeRouteEnterHandleShareOpenDontNext: function (to, from, next, type) {
    if (to.query.isShareOpen && to.query.isShareOpen !== 'false') {
      // ajax('/jv/share/anonymous/open', { data: { type: type } })
      // delete to.query.isShareOpen
      // to.params.isShareOpen = true
      return true
    } else {
      return false
    }
  },
  handleContentUrl: function (content) {
    var subStr = new RegExp('((http(s)?:)?//[a-zA-Z0-9\\.\\-]+(:\\d+)?(/[a-zA-Z0-9\\.\\-~!@|#$%^&*+?:_/=<>]*)?)', 'g')
    content = content.replace(/[<>]/g, function (c) { return { '<': '&lt;', '>': '&gt;' }[c] })
    content = content.replace(subStr, `<a class="content-url" href="$1" onclick="event.stopPropagation()"><span class="iconfont icon-icon_lianjie_min"></span>网络链接</a>`)

    return content
  },
  weixinShareHandle: function (payload = {}, config = {}) {
    let type = payload.type || ''
    global.wx.config({
      debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
      appId: config.appid, // 必填，公众号的唯一标识
      timestamp: config.timestamp, // 必填，生成签名的时间戳
      nonceStr: config.nonceStr, // 必填，生成签名的随机串
      signature: config.signature, // 必填，签名
      jsApiList: ['updateAppMessageShareData', 'updateTimelineShareData', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareQZone'] // 必填，需要使用的JS接口列表
    })

    global.wx.ready(function () {
      // global.wx.updateAppMessageShareData(
      //   {
      //     title: payload.title,
      //     desc: payload.desc,
      //     link: payload.url,
      //     imgUrl: payload.imgUrl
      //   },
      //   function (res) {
      //     if (type) {
      //       ajax('/jv/share/article/addPoint', { data: { type: type } })
      //     }
      //   }
      // )
      // global.wx.updateTimelineShareData(
      //   {
      //     title: payload.title,
      //     link: payload.url,
      //     imgUrl: payload.imgUrl
      //   },
      //   function (res) {
      //     if (type) {
      //       ajax('/jv/share/article/addPoint', { data: { type: type } })
      //     }
      //   }
      // )
      global.wx.onMenuShareTimeline({
        title: payload.title,
        link: payload.url,
        imgUrl: payload.imgUrl,
        success: function () {
          if (type) {
            ajax('/jv/share/article/addPoint', { data: { type: type } })
          }
        }
      })
      global.wx.onMenuShareAppMessage({
        title: payload.title, // 分享标题
        desc: payload.desc, // 分享描述
        link: payload.url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: payload.imgUrl, // 分享图标
        type: '', // 分享类型,music、video或link，不填默认为link
        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        success: function () {
          if (type) {
            ajax('/jv/share/article/addPoint', { data: { type: type } })
          }
        }
      })
      global.wx.onMenuShareQQ({
        title: payload.title, // 分享标题
        desc: payload.desc, // 分享描述
        link: payload.url, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: payload.imgUrl, // 分享图标
        success: function () {
          if (type) {
            ajax('/jv/share/article/addPoint', { data: { type: type } })
          }
        },
        cancel: function () {
          // 用户取消分享后执行的回调函数
        }
      })
      global.wx.onMenuShareQZone({
        title: payload.title, // 分享标题
        desc: payload.desc, // 分享描述
        link: payload.url, // 分享链接
        imgUrl: payload.imgUrl, // 分享图标
        success: function () {
          if (type) {
            ajax('/jv/share/article/addPoint', { data: { type: type } })
          }
        },
        cancel: function () {
          // 用户取消分享后执行的回调函数
        }
      })
    })
  },
  goLogin: function () {
    window.localStorage.loginBack = router.history.current.path
    router.replace({ path: '/login' })
  }
}
