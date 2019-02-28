const state = {
    addinformobjectdata: [],
    editinformobjectdata: [],
    quillContent: '',
    type: '',
    filter: []
    
}


const mutations = {
    setaddInformobject: (state,data)=>{
        state.addinformobjectdata = data
    },
    seteditInformobject: (state,data)=>{
        state.editinformobjectdata = data
    },
    setType: (state,data)=>{
        state.type = data
    },
    setFilter: (state,data)=>{
        state.filter = data
    },
    setquillContent: (state,data)=>{
        state.quillContent = data
    },
    cleardata: (state)=> {
        state.informobject = []
        state.quillContent = ''
    }
}


export default {
    namespaced: true,
    state,
    mutations,
}