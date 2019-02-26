import Vue from 'vue'
import Router from 'vue-router'
const Home = () => import('./views/Home.vue')
const Login = () => import('./views/Login.vue')
const Enroll = () => import('./views/Enroll.vue')
const Dynamic = () => import('./views/Dynamic.vue')
const Training = () => import('./views/Training.vue')
const Race = () => import('./views/Race.vue')
const Perform = () => import('./views/Perform.vue')
const Book = () => import('./views/Book.vue')
const Us = () => import('./views/Us.vue')
const EnrollNotice = () => import('./views/EnrollNotice.vue')
const EnrollManage = () => import('./views/EnrollManage.vue')
const Queryhall = () => import('./views/Queryhall.vue')
const Queryscore = () => import('./views/Queryscore.vue')
const EnrollApply = () => import('./views/EnrollApply.vue')
const EnrollApplySuccess = () => import('./views/EnrollApplySuccess.vue')
const EnrollPay = () => import('./views/EnrollPay.vue')
const EnrollDetail = () => import('./views/EnrollDetail.vue')
const ScoreResult = () => import('./views/ScoreResult.vue')
const DynamicDetail = () => import('./views/DynamicDetail.vue')
const MiniappDynamicDetail = () => import('./views/MiniappDynamicDetail.vue')
const MiniappMessageDetail = () => import('./views/MiniappMessageDetail.vue')

Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
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
            requiredLogin: true,
            title: '考级通知-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'apply',
          name: 'EnrollApply',
          component: EnrollApply,
          meta: {
            requiredLogin: true,
            title: '考级报名-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'applysuccess',
          name: 'EnrollApplySuccesss',
          component: EnrollApplySuccess,
          meta: {
            requiredLogin: true,
            title: '提交成功-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'pay',
          name: 'EnrollPay',
          component: EnrollPay,
          meta: {
            requiredLogin: true,
            title: '支付-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'detail',
          name: 'EnrollDetail',
          component: EnrollDetail,
          meta: {
            requiredLogin: true,
            title: '报名详情-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'manage',
          name: 'EnrollManage',
          component: EnrollManage,
          meta: {
            requiredLogin: true,
            title: '报名管理-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'queryhall',
          name: 'Queryhall',
          component: Queryhall,
          meta: {
            requiredLogin: false,
            title: '考场查询-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'queryscore',
          name: 'Queryscore',
          component: Queryscore,
          meta: {
            requiredLogin: false,
            title: '成绩查询-社会艺术等级考试-海南考区'
          }
        },
        {
          path: 'scoreresult',
          name: 'ScoreResult',
          component: ScoreResult,
          meta: {
            requiredLogin: true,
            meta: {
              requiredLogin: false,
              title: '成绩查询-社会艺术等级考试-海南考区'
            }
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
