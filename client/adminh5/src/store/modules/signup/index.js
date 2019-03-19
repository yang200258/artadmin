const state = {
    signDetail: [],  // 单个考生报名详情
    status: {},   //考生详情的状态数据
    signList: [],   // 查询的考生列表
    page: {},  //考生列表页面信息
}

const mutations = {
    setSignDetail: (state,data)=> {
        state.signDetail = data
    },
    setStatus: (state,data)=> {
        state.status = data
    },
    setSignList: (state,data)=> {
        state.signList = data
    },
    setPage: (state,data)=> {
        state.page = data
    }
}



export default {
    namespaced: true,
    state,
    mutations
}