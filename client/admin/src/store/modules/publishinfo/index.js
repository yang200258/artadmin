const state = {
    publishData: {cid:"",title:"",status: 1,cover_id:"",intro:"",content:""},
    quillContent: '',
    infoTypeOptions: [],
    titleNumber: 0,
    introductionNumber: 0
}

const getters = {
    getpublishData: state=> state.publishData,
    gettitleNumber: state=> state.publishData.title.length || 0,
    getintroductionNumber: state=> state.publishData.intro.length || 0
}

const mutations = {
    setPublishData: (state,data)=>{
        state.publishData = data
    },
    setquillContent: (state,data)=>{
        state.quillContent = data
    },
    setInfoTypeOptions: (state,data)=> {
        state.infoTypeOptions = data
    },
    cleardata: (state)=> {
        state.publishData = {cid:"",title:"",status:"",cover_id:"",intro:"",content:""}
        state.quillContent = ''
    }
}


export default {
    namespaced: true,
    state,
    mutations,
    getters
}