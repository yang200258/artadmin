const state = {
    addinformobjectdata: {uid_arr: [],inform: [],},
    editinformobjectdata: {uid_arr: [],inform: [],inform_id: ''},
    type: '',
    filter: []
    
}


const mutations = {
    setaddInformobject: (state,data)=>{
        state.addinformobjectdata.inform = data
    },
    seteditInformobject: (state,data)=>{
        state.editinformobjectdata.inform = data
    },
    setType: (state,data)=>{
        state.type = data
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