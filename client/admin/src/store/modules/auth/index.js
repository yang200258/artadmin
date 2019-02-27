import Cookies from 'js-cookie'
import axios from '@/util/ajax'
import Auth from '@/util/auth'
import Navlist from '../../../assets/js/navlist'

const state = {
    token: '',
    navList: [],
    nationality: [],  //国籍
    nation: [],    //民族
    domainOptions: [],  //报考专业,
    certificate: [],   //身份证/护照
    // levelOptions: [],    //报考级别
    organ: [] // 机构
}

const mutations = {
    // setNavList: (state, data) => {
    //     state.navList = data
    // },

    setToken: (state, data) => {
        console.log('tonkendata',data);
        if(data){
            Auth.setToken(data)
            Auth.setLoginStatus()
        } else {
            Auth.removeToken()
            Auth.removeLoginStatus()
        }
        state.token = data
    },
    setNavlist: (state,data)=> {
        state.navList = data
    },
    setNation(state,data) {
        state.nation = data
    },
    setDomainOptions(state,data) {
        state.domainOptions = data
    },
    setCertificate(state,data) {
        state.certificate = data
    },
    setNationality(state,data) {
        state.nationality = data
    },
    setOptions(state,data) {
        state.nation = data.nation
        state.domainOptions = data.domainOptions
        state.certificate = data.certificate
        state.nationality = data.nationality
    },
    setOrgan(state,data) {
        state.organ = data
    }
}

const actions = {
    // 账号登录
    login({ commit,dispatch }, userInfo) {
        return new Promise((resolve,reject) => {
            axios({
                url: '/login',
                method: 'post',
                data: {
                    ...userInfo
                }
            }).then(res => {
                console.log('登陆数据响应',res);
                if( res && !res.error){
                    commit('setToken', res.data.token)
                    commit('user/setName', res.data.username, { root: true })
                    window.localStorage.setItem('authData',JSON.stringify(res.data))
                    dispatch('getNavlist')
                    resolve(res)
                }else {
                    reject(res)
                }
            })
        });
    },
    //生成权限列表
    getNavlist: function({commit}){
        let list = []
        let authData = JSON.parse(window.localStorage.getItem('authData'))
        Navlist[0].data.forEach(item=> {
            if(authData[item.auth] == '1') {
                list.push(item)
            }
            if(item.auth == 'home' ) {
                list.push(item)
            }
        })
        commit('setNavlist', list)
    },
    //获取基本信息   如：民族，证件，报考专业
    getOption({commit}) {
        return new Promise((resolve,reject)=> {
            axios({
                url: '/option',
                method: 'post',
                data: {}
            }).then(res => {
                console.log('获取操作基本数据响应',res);
                if(res && !res.error) {
                    let { nationality, nation, major, certificate } = res.data
                    let domainOptions = []
                    for (let mj in major) {
                        domainOptions.push({key: mj,value:major[mj]})
                    }
                    commit('setDomainOptions',domainOptions)
                    commit('setCertificate',certificate)
                    commit('setNation',nation)
                    commit('setNationality',nationality)
                    window.localStorage.setItem('optionData',JSON.stringify({domainOptions,nation,nationality,certificate}))
                    resolve(res)
                } else {
                    reject(res)
                }
            }).catch(err=> {
                console.log(err);
            })
        })
    },
    //获取所有机构名称
    getOrgan({commit}) {
        return new Promise((resolve,reject)=> {
            axios({
                url: '/organ',
                method: 'post',
                data: {}
            }).then(res=> {
                if(res && !res.error) {
                    commit('setOrgan',res.data)
                    window.localStorage.setItem('organ',JSON.stringify(res.data))
                    resolve(res)
                } else {
                    reject(res)
                }
            }).catch(err=> {
                console.log(err);
            })
        })
    },

    // 登出
    logout({commit}) {
        return new Promise((resolve) => {
            commit('setToken', '')
            commit('user/setName', '', { root: true })
            commit('tagNav/removeTagNav', '', {root: true})
            resolve()
        })
    },

    // 重新获取用户信息及Token
    // TODO: 这里不需要提供用户名和密码，实际中请根据接口自行修改
    relogin({ commit, state}){
        return new Promise((resolve) => {
            console.log('重新登录触发');
            // 根据Token进行重新登录
            let token = Cookies.get('token'),
                userName = Cookies.get('userName')
            // 重新登录时校验Token是否存在，若不存在则获取
            if(!token){
                commit('setToken', state.token)
            }
            // 刷新/关闭浏览器再进入时获取用户名
            commit('user/setName', decodeURIComponent(userName), { root: true })
            resolve()
        })
    },

    // 获取新Token
    // getNewToken({commit, state}){
    //     return new Promise((resolve) => {
    //         axios({
    //             url: '/getToken',
    //             method: 'get',
    //             param: {
    //                 token: state.token
    //             }
    //         }).then((res) =>{
    //             commit("setToken", res.data.token)
    //             resolve()
    //         })
    //     })
    // },

    // 获取该用户的菜单列表
    // getNavList({commit}){
    //     return new Promise((resolve) =>{
    //         console.log('getNavList',navlist);
    //         commit("setNavList", navlist)
    //         resolve(navlist)
    //         axios({
    //             url: '/user/navlist',
    //             methods: 'post',
    //             data: {}
    //         }).then((res) => {
    //             console.log('getNavList',res);
    //             commit("setNavList", res)
    //             resolve(res)
    //         })
    //     })
    // },

    // 将菜单列表扁平化形成权限列表
    getPermissionList({state,dispatch}){
        return new Promise((resolve) =>{
            dispatch('getNavlist')
            let permissionList = []
            // 将菜单数据扁平化为一级
            function flatNavList(arr){
                console.log('arr',arr);
                for(let v of arr){
                    if(v.child && v.child.length){
                        if(v.path) {
                            permissionList.push({path: v.path,name: v.name})  
                        }
                        flatNavList(v.child)
                    } else{
                        permissionList.push(v)
                    }
                }
            }
            flatNavList(state.navList)
            resolve(permissionList)
        })
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    actions
}