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
}

export default util