const state = {
    addinformobjectdata: {uid_arr: [],inform: [],type: ''},
    editinformobjectdata: {uid_arr: [],inform: [],inform_id: '',type: ''},
    filter: []
    
}


const mutations = {
    setaddInformobject: (state,data)=>{
        state.addinformobjectdata.inform = data
    },
    seteditInformobject: (state,data)=>{
        state.editinformobjectdata.inform = data
    },
    setAddType: (state,data)=>{
        state.addinformobjectdata.type = data
    },
    setEditType: (state,data)=>{
        state.editinformobjectdata.type = data
    },
    setFilter: (state,data)=>{
        state.filter = data
    },
    setAddUid: (state,data)=> {
        state.addinformobjectdata.uid_arr = data
    },
    setEditUid: (state,data)=> {
        state.editinformobjectdata.uid_arr = data
    },
    setInformId: (state,data)=> {
        state.editinformobjectdata.inform_id = data
    }
}


export default {
    namespaced: true,
    state,
    mutations,
}