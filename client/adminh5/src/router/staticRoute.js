// const Layout = resolve => require( ['@/page/layout'],resolve)
// const staticRoute = [
//     {
//         path: '/',
//         redirect: '/login'
//     },
//     {
//         path: '/error',
//         component: resolve => require( ['@/page/error'],resolve),
//         children: [
//             {
//                 path: '401',
//                 component: resolve => require(['@/page/error/401'],resolve)
//             },
//             {
//                 path: '403',
//                 component: resolve => require(['@/page/error/403'],resolve)
//             },
//             {
//                 path: '404',
//                 component: resolve => require(['@/page/error/404'],resolve)
//             },
//             {
//                 path: '500',
//                 component: resolve => require(['@/page/error/500'],resolve)
//             }
//         ]
//     },
//     {
//         path: '/login',
//         component: resolve => require(['@/page/login'],resolve)
//     },
//     {
//         path: '/signup',
//         component: Layout,
//         children: [
//             {
//                 path: '',
//                 name: 'signup',
//                 component: resolve => require(['@/page/signup'],resolve),
//             },
//             {
//                 path: 'signInfo',
//                 name: 'signInfo',
//                 component: resolve => require(['@/page/signup/signInfo'],resolve),
//                 meta: {name: '考生详情'}
//             },
//             {
//                 path: 'signInfo/imginfo',
//                 name: 'imginfo',
//                 component: resolve => require(['@/page/signup/signInfo/imgInfo'],resolve),
//                 meta: {name: '下载证书'}
//             },
//         ]
//     },
//     {
//         path: '/test',
//         component: Layout,
//         children: [
//             {
//                 path: 'testInfo',
//                 name: 'testInfo',
//                 component: resolve => require(['@/page/test/testInfo'],resolve),
//                 meta: {name: '考试管理'}
//             },
//             {
//                 path: 'testInfo/addTest',
//                 name: 'addTest',
//                 component: resolve => require(['@/page/test/testInfo/addTest'],resolve),
//                 meta: {name: '添加考试'}
//             },
//             {
//                 path: 'testInfo/editTest',
//                 name: 'editTest',
//                 component: resolve => require(['@/page/test/testInfo/editTest'],resolve),
//                 meta: {name: '编辑考试'}
//             },
//             {
//                 path: 'positionInfo',
//                 name: 'positionInfo',
//                 component: resolve => require(['@/page/test/positionInfo'],resolve),
//                 meta: {name: '考场管理'}
//             },
            
//             {
//                 path: 'positionInfo/editLocation',
//                 name: 'editLocation',
//                 component: (resolve) => require(['@/page/test/positionInfo/editLocation'],resolve),
//                 meta: {name: '考生安排'}
//             }
//         ]
//     },
//     {
//         path: '/infoManagement',
//         component: Layout,
//         children: [
//             {
//                 path: 'publishInfo',
//                 component: (resolve) => require(['@/page/infomanagement/publishInfo'],resolve),
//                 meta: {name: '发布信息'}
//             },
//             {
//                 path: 'infoList',
//                 name: 'infoList',
//                 component: (resolve) => require(['@/page/infomanagement/infoList'],resolve),
//                 meta: {name: '信息管理'},
//             },
//             {
//                 path: 'editInfo',
//                 name: 'editInfo',
//                 component: (resolve) => require(['@/page/infomanagement/infoList/editInfo'],resolve),
//                 meta: {name: '编辑信息'}
//             },
            
//         ]
//     },
//     {
//         path: '/informManagement',
//         component: Layout,
//         children: [
//             {
//                 path: 'publishInform',
//                 name: 'publishInform',
//                 component: (resolve) => require(['@/page/informManagement/publishInform'],resolve),
//                 meta: {name: '发布通知'}
//             },
//             {
//                 path: 'publishInform/informObject',
//                 name: 'informObject',
//                 component: (resolve) => require(['@/page/informManagement/publishInform/informObject'],resolve),
//                 meta: {name: '通知对象'}
//             },
//             {
//                 path: 'informlist',
//                 name: 'informlist',
//                 component: (resolve) => require(['@/page/informManagement/informList'],resolve),
//                 meta: {name: '通知列表'}
//             },
//             {
//                 path: 'informlist/editinform',
//                 name: 'editInform',
//                 component: (resolve) => require(['@/page/informManagement/informList/editInform'],resolve),
//                 meta: {name: '编辑通知'}
//             },
//             {
//                 path: 'informlist/editinformobject',
//                 name: 'editinformObject',
//                 component: (resolve) => require(['@/page/informManagement/informList/editInform/informobject'],resolve),
//                 meta: {name: '编辑对象'}
//             }
//         ]
//     },
//     {
//         path: '/manager',
//         component: Layout,
//         children: [
//             {
//                 path: 'manager',
//                 name: 'manager',
//                 component: (resolve) => require(['@/page/manager/manager'],resolve),
//                 meta: {name: '管理员管理'}
//             },
//             {
//                 path: 'oganization',
//                 name: 'oganization',
//                 component: (resolve) => require(['@/page/manager/oganization'],resolve),
//                 meta: {name: '机构管理'},
//             },
//             {
//                 path: 'signTeacher',
//                 name: 'signTeacher',
//                 component: (resolve) => require(['@/page/manager/signTeacher'],resolve),
//                 meta: {name: '报名老师管理'}
//             },
//             {
//                 path: 'manager/editmanager',
//                 name: 'editmanager',
//                 component: (resolve) => require(['@/page/manager/manager/editmanager'],resolve),
//                 meta: {name: '编辑管理员'}
//             },
//             {
//                 path: 'manager/addmanager',
//                 name: 'addmanager',
//                 component: (resolve) => require(['@/page/manager/manager/addmanager'],resolve),
//                 meta: {name: '新增管理员'}
//             },
//             {
//                 path: 'signTeacher/addTeacher',
//                 name: 'addTeacher',
//                 component: (resolve) => require(['@/page/manager/signTeacher/addTeacher'],resolve),
//                 meta: {name: '新增老师'}
//             },
//             {
//                 path: 'signTeacher/editTeacher',
//                 name: 'editTeacher',
//                 component: (resolve) => require(['@/page/manager/signTeacher/editTeacher'],resolve),
//                 meta: {name: '编辑报名老师信息'}
//             },
//             {
//                 path: 'addOganization',
//                 name: 'addoganization',
//                 component: (resolve) => require(['@/page/manager/oganization/addOganization'],resolve),
//                 meta: {name: '新增机构'}
//             },
//             {
//                 path: 'editOganization',
//                 name: 'editOganization',
//                 component: (resolve) => require(['@/page/manager/oganization/editOganization'],resolve),
//                 meta: {name: '编辑机构信息'}
//             }
//         ]
//     },
//     {
//         path: '/optionRecord',
//         component: Layout,
//         children: [
//             {
//                 path: '',
//                 name: 'optionRecord',
//                 component: (resolve) => require(['@/page/optionRecord'],resolve),
//                 meta: {name: '操作记录'}
                
//             }
//         ]
//     }
// ]

// export default staticRoute


 import Layout from '@/page/layout'
 import login from  '@/page/login'
 import signup from  '@/page/signup'
 import signInfo from  '@/page/signup/signInfo'
 import imgInfo from  '@/page/signup/signInfo/imgInfo'
 import testInfo from  '@/page/test/testInfo'
 import addTest from  '@/page/test/testInfo/addTest'
 import editTest from  '@/page/test/testInfo/editTest'
 import positionInfo from  '@/page/test/positionInfo'
 import editLocation from  '@/page/test/positionInfo/editLocation'
 import publishInfo from  '@/page/infomanagement/publishInfo'
 import infoList from  '@/page/infomanagement/infoList'
 import editInfo from  '@/page/infomanagement/infoList/editInfo'
 import publishInform from  '@/page/informManagement/publishInform'
 import addinformObject from  '@/page/informManagement/publishInform/informObject'

 import informList from  '@/page/informManagement/informList'
 import editInform from  '@/page/informManagement/informList/editInform'
 import editinformobject from  '@/page/informManagement/informList/editInform/informobject'
 import manager from  '@/page/manager/manager'
 import oganization from  '@/page/manager/oganization'
 import signTeacher from  '@/page/manager/signTeacher'
 import editmanager from  '@/page/manager/manager/editmanager'
 import addmanager from  '@/page/manager/manager/addmanager'
 import addTeacher from  '@/page/manager/signTeacher/addTeacher'
 import editTeacher from  '@/page/manager/signTeacher/editTeacher'
 import addOganization from  '@/page/manager/oganization/addOganization'
 import editOganization from  '@/page/manager/oganization/editOganization'
 import optionRecord from '@/page/optionRecord'
const staticRoute = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/error',
        component: resolve => require( ['@/page/error'],resolve),
        children: [
            {
                path: '401',
                component: resolve => require(['@/page/error/401'],resolve)
            },
            {
                path: '403',
                component: resolve => require(['@/page/error/403'],resolve)
            },
            {
                path: '404',
                component: resolve => require(['@/page/error/404'],resolve)
            },
            {
                path: '500',
                component: resolve => require(['@/page/error/500'],resolve)
            }
        ]
    },
    {
        path: '/login',
        component: login
    },
    {
        path: '/signup',
        component: Layout,
        children: [
            {
                path: '',
                name: 'signup',
                component: signup,
            },
            {
                path: 'signInfo',
                name: 'signInfo',
                component: signInfo,
                meta: {name: '考生详情'}
            },
            {
                path: 'signInfo/imginfo',
                name: 'imginfo',
                component: imgInfo,
                meta: {name: '下载证书'}
            },
        ]
    },
    {
        path: '/test',
        component: Layout,
        children: [
            {
                path: '',
                name: 'testInfo',
                component: testInfo,
                meta: {name: '考试管理'}
            },
            {
                path: 'testInfo/addTest',
                name: 'addTest',
                component: addTest,
                meta: {name: '添加考试'}
            },
            {
                path: 'testInfo/editTest',
                name: 'editTest',
                component: editTest,
                meta: {name: '编辑考试'}
            },
            {
                path: 'positionInfo',
                name: 'positionInfo',
                component: positionInfo,
                meta: {name: '考场管理'}
            },
            
            {
                path: 'positionInfo/editLocation',
                name: 'editLocation',
                component: editLocation,
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
                name: 'publishInfo',
                component: publishInfo,
                meta: {name: '发布信息'}
            },
            {
                path: '',
                name: 'infoList',
                component: infoList,
                meta: {name: '信息管理'},
            },
            {
                path: 'editInfo',
                name: 'editInfo',
                component: editInfo,
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
                component: publishInform,
                meta: {name: '发布通知'}
            },
            {
                path: 'publishInform/informObject',
                name: 'addinformObject',
                component: addinformObject,
                meta: {name: '通知对象'}
            },
            {
                path: '',
                name: 'informlist',
                component: informList,
                meta: {name: '通知列表'}
            },
            {
                path: 'informlist/editinform',
                name: 'editInform',
                component: editInform,
                meta: {name: '编辑通知'}
            },
            {
                path: 'informlist/editinformobject',
                name: 'editinformObject',
                component: editinformobject,
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
                component: manager,
                meta: {name: '管理员管理'}
            },
            {
                path: 'oganization',
                name: 'oganization',
                component: oganization,
                meta: {name: '机构管理'},
            },
            {
                path: 'signTeacher',
                name: 'signTeacher',
                component: signTeacher,
                meta: {name: '报名老师管理'}
            },
            {
                path: 'manager/editmanager',
                name: 'editmanager',
                component: editmanager,
                meta: {name: '编辑管理员'}
            },
            {
                path: 'manager/addmanager',
                name: 'addmanager',
                component: addmanager,
                meta: {name: '新增管理员'}
            },
            {
                path: 'signTeacher/addTeacher',
                name: 'addTeacher',
                component: addTeacher,
                meta: {name: '新增老师'}
            },
            {
                path: 'signTeacher/editTeacher',
                name: 'editTeacher',
                component:editTeacher,
                meta: {name: '编辑报名老师信息'}
            },
            {
                path: 'addOganization',
                name: 'addoganization',
                component:addOganization,
                meta: {name: '新增机构'}
            },
            {
                path: 'editOganization',
                name: 'editOganization',
                component: editOganization,
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
                component: optionRecord,
                meta: {name: '操作记录'}
                
            }
        ]
    }
]

export default staticRoute