// const Layout = () => import('@/page/layout')
import Layout from '@/page/layout'

const staticRoute = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/error',
        component: () => import( '@/page/error'),
        children: [
            {
                path: '401',
                component: () => import('@/page/error/401')
            },
            {
                path: '403',
                component: () => import( '@/page/error/403')
            },
            {
                path: '404',
                component: () => import( '@/page/error/404')
            },
            {
                path: '500',
                component: () => import( '@/page/error/500')
            }
        ]
    },
    {
        path: '/login',
        component: () => import('@/page/login')
    },
    {
        path: '/home',
        component: Layout,
        children : [
            {
                path: '',
                component: () => import('@/page/home'),
                meta: {name: '首页'}
            }
        ]
        
    },
    {
        path: '/signup',
        component: Layout,
        children: [
            {
                path: '',
                name: 'signup',
                component: () => import('@/page/signup')
            },
            {
                path: 'signInfo',
                name: 'signInfo',
                component: () => import('@/page/signup/signInfo'),
                meta: {name: '考生详情'}
            },
            {
                path: 'signInfo/imginfo',
                name: 'imginfo',
                component: () => import('@/page/signup/signInfo/imginfo'),
                meta: {name: '下载证书'}
            },
        ]
    },
    {
        path: '/test',
        component: Layout,
        children: [
            {
                path: 'testInfo',
                name: 'testInfo',
                component: () => import('@/page/test/testInfo'),
                meta: {name: '考试管理'}
            },
            {
                path: 'testInfo/addTest',
                name: 'addTest',
                component: () => import('@/page/test/testInfo/addTest'),
                meta: {name: '添加考试'}
            },
            {
                path: 'testInfo/editTest',
                name: 'editTest',
                component: () => import('@/page/test/testInfo/editTest'),
                meta: {name: '编辑考试'}
            },
            {
                path: 'positionInfo',
                name: 'positionInfo',
                component: () => import('@/page/test/positionInfo'),
                meta: {name: '考场管理'}
            },
            
            {
                path: 'positionInfo/editLocation',
                name: 'editLocation',
                component: () => import('@/page/test/positionInfo/editLocation'),
                meta: {name: '考生安排'}
            }
        ]
    },
    {
        path: '/infoManagement',
        component: Layout,
        children: [
            {
                path: 'publishInfo',
                component: () => import('@/page/infoManagement/publishInfo'),
                meta: {name: '发布信息'}
            },
            {
                path: 'infoList',
                name: 'infoList',
                component: () => import('@/page/infoManagement/infoList'),
                meta: {name: '信息管理'},
            },
            {
                path: 'editInfo',
                name: 'editInfo',
                component: () => import('@/page/infoManagement/infoList/editInfo'),
                meta: {name: '编辑信息'}
            },
            
        ]
    },
    {
        path: '/informManagement',
        component: Layout,
        children: [
            {
                path: 'publishInform',
                name: 'publishInform',
                component: () => import('@/page/informManagement/publishInform'),
                meta: {name: '发布通知'}
            },
            {
                path: 'publishInform/informObject',
                name: 'informObject',
                component: () => import('@/page/informManagement/publishInform/informObject'),
                meta: {name: '通知对象'}
            },
            {
                path: 'informlist',
                name: 'informlist',
                component: () => import('@/page/informManagement/informlist'),
                meta: {name: '通知列表'}
            },
            {
                path: 'informlist/editinform',
                name: 'editInform',
                component: () => import('@/page/informManagement/informlist/editinform'),
                meta: {name: '编辑通知'}
            },
            {
                path: 'informlist/editinformobject',
                name: 'editinformObject',
                component: () => import('@/page/informManagement/informlist/editinform/informobject'),
                meta: {name: '编辑对象'}
            }
        ]
    },
    {
        path: '/manager',
        component: Layout,
        children: [
            {
                path: 'manager',
                name: 'manager',
                component: () => import('@/page/manager/manager'),
                meta: {name: '管理员管理'}
            },
            {
                path: 'oganization',
                name: 'oganization',
                component: () => import('@/page/manager/oganization'),
                meta: {name: '机构管理'}
            },
            {
                path: 'signTeacher',
                name: 'signTeacher',
                component: () => import('@/page/manager/signTeacher'),
                meta: {name: '报名老师管理'}
            },
            {
                path: 'manager/editmanager',
                name: 'editmanager',
                component: () => import('@/page/manager/manager/editmanager'),
                meta: {name: '编辑管理员'}
            },
            {
                path: 'manager/addmanager',
                name: 'addmanager',
                component: () => import('@/page/manager/manager/addmanager'),
                meta: {name: '新增管理员'}
            },
            {
                path: 'signTeacher/addTeacher',
                name: 'addTeacher',
                component: () => import('@/page/manager/signTeacher/addTeacher'),
                meta: {name: '新增老师'}
            },
            {
                path: 'signTeacher/editTeacher',
                name: 'editTeacher',
                component: () => import('@/page/manager/signTeacher/editTeacher'),
                meta: {name: '编辑报名老师信息'}
            },
            {
                path: 'oganization/addOganization',
                name: 'addoganization',
                component: () => import('@/page/manager/oganization/addOganization'),
                meta: {name: '新增机构'}
            },
            {
                path: 'oganization/editOganization',
                name: 'editOganization',
                component: () => import('@/page/manager/oganization/editOganization'),
                meta: {name: '编辑机构信息'}
                
            }
        ]
    },
    {
        path: '/optionRecord',
        component: Layout,
        children: [
            {
                path: '',
                name: 'optionRecord',
                component: () => import('@/page/optionRecord'),
                meta: {name: '操作记录'}
                
            }
        ]
    }
]

export default staticRoute