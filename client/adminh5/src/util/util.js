// import Cookies from 'js-cookie'

const util = {
    //根据数组中值删除数组某一项
    delIdArray: function(arr,id){
        for(let i = 0;i<arr.length;i++) {
            if(arr[i].id == id) {
                arr.splice(i,1)
            }
        }
        console.log('this.arr',arr);
    },
    //处理时间格式
    filterDate(date){
        if(date) {
            const year = date.getFullYear()
            const month = ((date.getMonth() + 1) >= 10) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))
            const day = date.getDate() >= 10 ? date.getDate() : '0' + date.getDate()
            return year + '-' + month + '-' + day
        } else {
            return ''
        }
    },
    //处理时间格式
    filterDateTime(date){
        if(date) {
            const year = date.getFullYear()
            const month = ((date.getMonth() + 1) >= 10) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))
            const day = date.getDate() >= 10 ? date.getDate() : '0' + date.getDate()
            const h = (date.getHours() >= 10) ? date.getHours() : ('0' + date.getHours())
            const m = (date.getMinutes() >= 10) ? date.getMinutes() : ('0' + date.getMinutes())
            const s = (date.getSeconds() >= 10) ? date.getSeconds() : ('0' + date.getSeconds())
            return year + '-' + month + '-' + day + ' ' + h + ':' + m + ':' + s 
        } else {
            return ''
        }
    },
    //扁平化数据
    flatData: function(arr) {
        return new Promise((resolve)=>{
        const list = {}
        Object.keys(arr).forEach(keyo=>{
            if(typeof(arr[keyo]) == 'object' && arr[keyo]){
                if(arr[keyo] !== 'null') {
                    Object.keys(arr[keyo]).forEach(key=>{
                        list[keyo + '_' + key] = arr[keyo][key]
                    }) 
                }
            }  else{
                list[keyo] = arr[keyo]
            }
        })
        resolve(list)
        })
    },
    //考试信息数据处理
    //将多项数组对象合并同类项
    mergeJson: function(arr){
        return new Promise((resolve)=> {
            const map = {}
            for(const site of arr) {
                if(!map.hasOwnProperty(site.address)) {
                    map[site.address] = site
                    const val = map[site.address].exam_time
                    map[site.address].exam_time = [val]
                } else {
                    map[site.address].exam_time.push(site.exam_time)
                }
            }
            function transform(obj) {
                let arr = []
                for(let item in obj) {
                    arr.push(obj[item])
                }
                return arr
            }
            resolve(transform(map))
        })
        
    },
    //将合并后数组继续分类为相同考点
    turn: function(arr){
        return new Promise((resolve)=> {
            let o = {}
            arr.forEach(item=> {
                let array = o[item['address']] || []
                array.push(item)
                o[item['address']] = array
            })
            resolve(o)
        })
    },
    //将处理后数据最终转换为所需
    turnFinal: function(o){
        return new Promise(resolve=> {
            let site = []
            for(let item in o) {
                if(o[item].length == 1) {
                    if(o[item].exam_time.length == 1) {
                        site.push({address: o[item].address,time1: o[item].exam_time[0],time: [],rooms: [],key: Date.now()})
                    } else {
                        const time = []
                        o[item].exam_time.forEach((t,i)=> {
                            if(i>=1) {
                                time.push({value:t,key: Date.now()})
                            }
                        })
                        site.push({address: o[item][0].address,time1: o[item].exam_time[0],time: time,rooms: [],key: Date.now()})
                    }
                } else {
                        let time1 = ''
                        let time = []
                        let rooms = []
                        let times = []
                        // let address = 
                        o[item].forEach((l,i) => {
                        if(i == 0) {
                            time1 = l.exam_time[0]
                            if(l.exam_time.length == 1) {
                                time = []
                            } else {
                                l.exam_time.forEach((t,m)=> {
                                    if(m >=1) {
                                        time.push({value: t,key: Date.now()})
                                    }
                                })
                            }
                        } else {
                            rooms.time1 = l.exam_time[0]
                            if(l.exam_time.length == 1) {
                                rooms.times = []
                                // rooms.push({time1:rooms_time1,key: Date.now() })
                            } else {
                                l.exam_time.forEach((s,i)=> {
                                    if(i >=1) {
                                        times.push({value: s,key: Date.now()})
                                        
                                    }
                                })
                            }
                            rooms.times = times
                        }
                    })
                    site.push({address: o[item][0].address,time1: time1,time: time,rooms: rooms,key: Date.now()})
                }
            }
            console.log(site);
            resolve(site)
            
        })
    },
}

export default util