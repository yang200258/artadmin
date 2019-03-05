const state = {
    examSite: {
        address: "",
        time1: '',
        time: [{value: ''}],
        rooms: [{time1: '',times: [{value: ''}]}],
        sites: [{site: '',times:[{value: ''}]}]
    },
    baseinfo: {number: '',name: '',applyTime: [],examTime: []},
    rules: {address:[{required: true, message: '请输入考试地点', trigger: 'blur'}],time1:[{type: 'date',required: true, message: '请设置考试时间', trigger: 'change'}]}
}


const mutations = {
    setExamSite: (state,data)=> {
        state.examSite = data
    }
}



export default {
    namespaced: true,
    state,
    mutations,
}