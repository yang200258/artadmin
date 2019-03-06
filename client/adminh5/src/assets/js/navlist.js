var data = [
    // {
    //     path: '/home',
    //     name: '首页',
    //     auth: 'home'
    // },
    {
        name: '报名管理',
        path: '/signup',
        auth: 'apply',
    },
    {
        name: '考试管理',
        auth: 'exam',
        child: [
            {
                path: '/test/testInfo',
                name: '考试管理',
            },
            {
                path: '/test/positionInfo',
                name: '考场安排',
            }
        ]
    },
    {
        name: '信息管理',
        auth: 'msg',
        path: '/infoManagement/infoList',
        // child: [
        //     {
        //         path: '/infoManagement/publishInfo',
        //         name: '发布信息',
        //     },
        //     {
        //         path: '/infoManagement/infoList',
        //         name: '信息列表',
        //     }
        // ]
    },
    {
        name: '通知管理',
        auth: 'inform',
        path: '/informManagement/informlist',
        // child: [
        //     {
        //         path: '/informManagement/publishInform',
        //         name: '发布通知',
        //     },
        //     {
        //         path: '/informManagement/informlist',
        //         name: '通知列表',
        //     }
        // ]
    },
    {
        name: '管理员管理',
        auth: 'admin',
        child: [
            {
                path: '/manager/manager',
                name: '管理员管理',
            },
            {
                path: '/manager/oganization',
                name: '机构管理',
            },
            {
                path: '/manager/signTeacher',
                name: '报名老师管理',
            }
        ]
    },
    {
        name: '操作记录',
        auth: 'option',
        path: '/optionRecord'
    }
]

export default [{
    data: data
}]