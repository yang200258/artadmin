import Vue from 'vue'
import Router from 'vue-router'

// import Home from './views/Home.vue'
// import Login from './views/Login.vue'
// import Enroll from './views/Enroll.vue'
// import Dynamic from './views/Dynamic.vue'
// import Training from './views/Training.vue'
// import Race from './views/Race.vue'
// import Perform from './views/Perform.vue'
// import Book from './views/Book.vue'
// import Us from './views/Us.vue'
// import EnrollNotice from './views/EnrollNotice.vue'
// import EnrollManage from './views/EnrollManage.vue'
// import Queryhall from './views/Queryhall.vue'
// import Queryscore from './views/Queryscore.vue'
// import EnrollApply from './views/EnrollApply.vue'
// import EnrollApplySuccess from './views/EnrollApplySuccess.vue'
// import EnrollPay from './views/EnrollPay.vue'
// import EnrollDetail from './views/EnrollDetail.vue'
// import ScoreResult from './views/ScoreResult.vue'
// import DynamicDetail from './views/DynamicDetail.vue'
// import MiniappDynamicDetail from './views/MiniappDynamicDetail.vue'
// import MiniappMessageDetail from './views/MiniappMessageDetail.vue'
// import MiniappRegulation from './views/MiniappRegulation.vue'
// import MiniappUs from './views/MiniappUs.vue'
// import Regulation from './views/Regulation.vue'

const Home = resolve => require(['./views/Home.vue'], resolve)
const Login = resolve => require(['./views/Login.vue'], resolve)
const Enroll = resolve => require(['./views/Enroll.vue'], resolve)
const Dynamic = resolve => require(['./views/Dynamic.vue'], resolve)
const Training = resolve => require(['./views/Training.vue'], resolve)
const Race = resolve => require(['./views/Race.vue'], resolve)
const Perform = resolve => require(['./views/Perform.vue'], resolve)
const Book = resolve => require(['./views/Book.vue'], resolve)
const Us = resolve => require(['./views/Us.vue'], resolve)
const EnrollNotice = resolve => require(['./views/EnrollNotice.vue'], resolve)
const EnrollManage = resolve => require(['./views/EnrollManage.vue'], resolve)
const Queryhall = resolve => require(['./views/Queryhall.vue'], resolve)
const Queryscore = resolve => require(['./views/Queryscore.vue'], resolve)
const EnrollApply = resolve => require(['./views/EnrollApply.vue'], resolve)
const EnrollApplySuccess = resolve => require(['./views/EnrollApplySuccess.vue'], resolve)
const EnrollPay = resolve => require(['./views/EnrollPay.vue'], resolve)
const EnrollDetail = resolve => require(['./views/EnrollDetail.vue'], resolve)
const ScoreResult = resolve => require(['./views/ScoreResult.vue'], resolve)
const DynamicDetail = resolve => require(['./views/DynamicDetail.vue'], resolve)
const MiniappDynamicDetail = resolve => require(['./views/MiniappDynamicDetail.vue'], resolve)
const MiniappMessageDetail = resolve => require(['./views/MiniappMessageDetail.vue'], resolve)
const MiniappRegulation = resolve => require(['./views/MiniappRegulation.vue'], resolve)
const MiniappUs = resolve => require(['./views/MiniappUs.vue'], resolve)
const Regulation = resolve => require(['./views/Regulation.vue'], resolve)

// 路由相关说明:
// 关于我们: /us
// 考试简章: /regulation
// 信息详情: /dynamicdetail?id=xxx
// 小程序访问->关于我们: /miniappus
// 小程序访问->考试简章: /miniappregulation
// 小程序访问->信息详情: /miniappdynamic?id=xxx
// 小程序访问->通知详情: /miniappmessage?id=xxx

Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: (process.env.NODE_ENV === 'production') ? '/home/' : '/',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: {
        requiredLogin: false,
        title: '社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {
        requiredLogin: false,
        title: '登录-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/enroll',
      name: 'Enroll',
      component: Enroll,
      meta: {
        requiredLogin: false,
        title: '考级报名-社会艺术等级考试-海南考区'
      },
      children: [
        {
          path: 'notice',
          name: 'EnrollNotice',
          component: EnrollNotice,
          meta: {
            actived: 'EnrollNotice',
            requiredLogin: true,
            title: '考级通知-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'apply',
          name: 'EnrollApply',
          component: EnrollApply,
          meta: {
            actived: 'EnrollNotice',
            requiredLogin: true,
            title: '考级报名-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'applysuccess',
          name: 'EnrollApplySuccesss',
          component: EnrollApplySuccess,
          meta: {
            actived: 'EnrollNotice',
            requiredLogin: true,
            title: '提交成功-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'pay',
          name: 'EnrollPay',
          component: EnrollPay,
          meta: {
            actived: 'EnrollManage',
            requiredLogin: true,
            title: '支付-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'detail',
          name: 'EnrollDetail',
          component: EnrollDetail,
          meta: {
            actived: 'EnrollManage',
            requiredLogin: true,
            title: '报名详情-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'manage',
          name: 'EnrollManage',
          component: EnrollManage,
          meta: {
            actived: 'EnrollManage',
            requiredLogin: true,
            title: '报名管理-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'queryhall',
          name: 'Queryhall',
          component: Queryhall,
          meta: {
            actived: 'Queryhall',
            requiredLogin: false,
            title: '考场查询-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'queryscore',
          name: 'Queryscore',
          component: Queryscore,
          meta: {
            actived: 'Queryscore',
            requiredLogin: false,
            title: '成绩查询-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'scoreresult',
          name: 'ScoreResult',
          component: ScoreResult,
          meta: {
            actived: 'Queryscore',
            requiredLogin: true,
            title: '成绩查询-社会艺术等级考试-海南考区'
          }
        }
      ]
    },
    {
      path: '/dynamic',
      name: 'Dynamic',
      component: Dynamic,
      meta: {
        requiredLogin: false,
        title: '考级动态-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/training',
      name: 'Training',
      component: Training,
      meta: {
        requiredLogin: false,
        title: '师资培训-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/race',
      name: 'Race',
      component: Race,
      meta: {
        requiredLogin: false,
        title: '大赛动态-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/perform',
      name: 'Perform',
      component: Perform,
      meta: {
        requiredLogin: false,
        title: '艺术团表演-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/book',
      name: 'Book',
      component: Book,
      meta: {
        requiredLogin: false,
        title: '考级教材-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/us',
      name: 'Us',
      component: Us,
      meta: {
        requiredLogin: false,
        title: '关于我们-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/dynamicdetail',
      name: 'DynamicDetail',
      component: DynamicDetail,
      meta: {
        requiredLogin: false,
        title: '社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/miniappdynamic',
      name: 'MiniappDynamicDetail',
      component: MiniappDynamicDetail,
      meta: {
        requiredLogin: false,
        title: '社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/miniappmessage',
      name: 'MiniappMessageDetail',
      component: MiniappMessageDetail,
      meta: {
        requiredLogin: false,
        title: '社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/miniappregulation',
      name: 'MiniappRegulation',
      component: MiniappRegulation,
      meta: {
        requiredLogin: false,
        title: '考级简章-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/miniappus',
      name: 'MiniappUs',
      component: MiniappUs,
      meta: {
        requiredLogin: false,
        title: '关于我们-社会艺术等级考试-海南考区'
      }
    },
    {
      path: '/regulation',
      name: 'Regulation',
      component: Regulation,
      meta: {
        actived: 'Regulation',
        requiredLogin: false,
        title: '考试简章-社会艺术等级考试-海南考区'
      }
    }
  ]
})

router.beforeEach((to, from, next) => {
  if (to.meta.requiredLogin && !(window.localStorage.token && window.localStorage.username)) {
    window.localStorage.loginBack = to.path
    next({ path: '/login', replace: true })
  } else {
    if (to.meta.title) {
      document.title = to.meta.title
    } else {
      document.title = '\u200E'
    }
    next()
  }
})

export default router
