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
        console.log('处理时间格式',date);
        if(date && date.getFullYear()) {
            const year = date.getFullYear()
            const month = ((date.getMonth() + 1) >= 10) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))
            const day = date.getDate() >= 10 ? date.getDate() : '0' + date.getDate()
            const h = (date.getHours() >= 10) ? date.getHours() : ('0' + date.getHours())
            const m = (date.getMinutes() >= 10) ? date.getMinutes() : ('0' + date.getMinutes())
            const s = (date.getSeconds() >= 10) ? date.getSeconds() : ('0' + date.getSeconds())
            return year + '-' + month + '-' + day + ' ' + h + ':' + m + ':' + s 
        } else {
            return date
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
            arr.forEach(site=> {
                if(!map.hasOwnProperty(site.address+site.room)) {
                    map[site.address+site.room] = site
                    const val = map[site.address+site.room].exam_time
                    map[site.address+site.room].exam_time = [{value: val,id:map[site.address+site.room].id}]
                } else {
                    map[site.address+site.room].exam_time.push({value:site.exam_time,id:site.id})
                }
            })
            // console.log(map);
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
            console.log('arr',arr);
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
                console.log(o[item].length);
                if(o[item].length == 1) {
                    const time = []
                    o[item][0].exam_time.forEach((t,i)=> {
                        if(i>=1) {
                            time.push({value:t.value,key: Date.now(),id: t.id })
                        }
                    })
                    site.push({address: o[item][0].address,time1: o[item][0].exam_time[0],time: time,rooms: [],key: Date.now()})
                    console.log('1个考场时的数据',site);
                } else {
                        let time1 = ''
                        let time = []
                        let room = {}
                        let rooms = []
                        let times = []
                        o[item].forEach((l,i) => {
                            if(i == 0) {
                                time1 = {value: l.exam_time[0].value,id:l.exam_time[0].id}
                                if(l.exam_time.length == 1) {
                                    time = []
                                } else {
                                    l.exam_time.forEach((t,m)=> {
                                        if(m >=1) {
                                            time.push({value: t.value,key: Date.now(),id: t.id})
                                        }
                                    })
                                }
                            } else {
                                room.time1 = {value: l.exam_time[0].value,id: l.exam_time[0].id}
                                if(l.exam_time.length == 1) {
                                    room.times = []
                                } else {
                                    l.exam_time.forEach((s,i)=> {
                                        if(i >=1) {
                                            times.push({value: s.value,key: Date.now(),id: s.id})
                                        }
                                    })
                                }
                                room.times = times
                            }
                    })
                    rooms.push(room)
                    site.push({address: o[item][0].address,time1: time1,time: time,rooms: rooms,key: Date.now()})
                }
        }
            console.log(site);
            resolve(site)
        })
    },
}

export default util