const state = {
    managerdata: {},
    rightlist: []
}


const mutations = {
    setManagerdata: (state,data)=> {
        state.managerdata = data
    },
    setRightlist: (state,data)=> {
        state.rightlist = data
    }
}


export default {
    namespaced: true,
    state,
    mutations
}