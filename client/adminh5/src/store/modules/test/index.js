const state = {
    examSite: [{address: "",time1: {value: ''},time: [],rooms: []}],
    baseinfo: {number: '',name: '',applyTime: [],examTime: []},
    rules: {address:[{required: true, message: '请输入考试地点', trigger: 'blur'}],time1:[{type: 'date',required: true, message: '请设置考试时间', trigger: 'change'}]},
    isEdit: false
}


const mutations = {
    setExamSite: (state,data)=> {
        state.examSite = data
    },
    setBaseinfo: (state,data)=> {
        state.baseinfo = data
    },
    initExamSite: (state)=> {
        state.examSite = [{address: "",time1: {value: ''},time: [],rooms: []}]
    },
    initBaseinfo: (state)=> {
        state.baseinfo = {number: '',name: '',applyTime: [],examTime: []}
    },
    setEdit: (state,data)=> {
        state.isEdit = data
    }
}



export default {
    namespaced: true,
    state,
    mutations,
}