(window.webpackJsonp=window.webpackJsonp||[]).push([[27],{BOi7:function(e,t,a){},DgvE:function(e,t,a){"use strict";var n={delIdArray:function(e,t){for(var a=0;a<e.length;a++)e[a].id==t&&e.splice(a,1);console.log("this.arr",e)},filterDate:function(e){return e?e.getFullYear()+"-"+(e.getMonth()+1>=10?e.getMonth()+1:"0"+(e.getMonth()+1))+"-"+(e.getDate()>=10?e.getDate():"0"+e.getDate()):""},filterDateTime:function(e){return e?e.getFullYear()+"-"+(e.getMonth()+1>=10?e.getMonth()+1:"0"+(e.getMonth()+1))+"-"+(e.getDate()>=10?e.getDate():"0"+e.getDate())+" "+(e.getHours()>=10?e.getHours():"0"+e.getHours())+":"+(e.getMinutes()>=10?e.getMinutes():"0"+e.getMinutes())+":"+(e.getSeconds()>=10?e.getSeconds():"0"+e.getSeconds()):""}};t.a=n},LalW:function(e,t,a){"use strict";var n=a("BOi7");a.n(n).a},mopR:function(e,t,a){"use strict";a.r(t);var n=a("L2JU"),l=a("DgvE"),o=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var a=arguments[t];for(var n in a)Object.prototype.hasOwnProperty.call(a,n)&&(e[n]=a[n])}return e},s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},i={data:function(){return{name:"",domain:[],level:"",levelOptions:[],signTime:[],id_type:"",idCardOptions:[{value:"1",label:"身份证"},{value:"2",label:"护照"}],id_number:"",organ_name:"",teacher_name:"",status:"",verifyOptions:[{value:"1",label:"待审核"},{value:"2",label:"不通过"},{value:"4",label:"已通过"},{value:"3",label:"无需审核"}],plan:"",progressOptions:[{value:"1",label:"审核中"},{value:"3",label:"已失效"},{value:"2",label:"待缴费"},{value:"4",label:"已缴费"}],postpone:"",islostTestOptions:[{value:"0",label:"全部"},{value:"2",label:"是"},{value:"1",label:"否"}],head:[{key:"name",name:"考生姓名"},{key:"domain",name:"报考专业"},{key:"level",name:"报考级别"},{key:"create_at",name:"报名时间"},{key:"apply_no",name:"报名编号"},{key:"id_type",name:"证件类型"},{key:"id_number",name:"证件号码"},{key:"room",name:"考场"},{key:"user_name",name:"负责报名机构"},{key:"user_organ_name",name:"负责报名老师"},{key:"status",name:"审核状态"},{key:"plan",name:"当前进度"},{key:"postpone",name:"是否存在缺考顺延"}],isLoading:!1}},mounted:function(){},methods:{handleCurrentChange:function(e){this.queryInfo(e)},queryInfo:function(e){var t=this;this.$store.commit("signup/setPage",{}),this.$store.commit("signup/setSignList",[]);var a=this.domain,n=this.name,o=this.level,s=this.id_type,i=this.id_numbe,r=this.status,u=this.plan,c=this.postpone,p=this.organ_name,m=this.teacher_name,v=this.signTime,d=l.a.filterDate(v[0]),g=l.a.filterDate(v[1]);e=e||1,this.isLoading=!0;var f=[];this.$axios({url:"/apply/list",method:"post",data:{domain:a,name:n,level:o,id_type:s,id_numbe:i,status:r,plan:u,postpone:c,organ_name:p,teacher_name:m,start_time:d,end_time:g,pn:e}}).then(function(e){if(console.log(e),console.log("获取报名列表数据",e),e&&!e.error){var a=e.data.list;a.length>0&&a.forEach(function(e){t.flatData(e).then(function(e){e.examsite1&&(e.room1="考场1"+e.examsite1.address+"("+e.examsite1.room+")；"),e.examsite2&&(e.room2="考场2"+e.examsite2.address+"("+e.examsite2.room+")"),e.examsite1&&!e.examsite2&&(e.room=e.room1),e.examsite1&&e.examsite2&&(e.room=e.room1+e.room2),f.push(e)})}),t.$store.commit("signup/setPage",e.data.page),t.$store.commit("signup/setSignList",f)}t.isLoading=!1,console.log("查询到的考生数据",f)}).catch(function(e){console.log(e),t.isLoading=!1})},signDetail:function(e){this.$router.push({name:"signInfo",params:{id:e.row.id,status:e.row.status,plan:e.row.plan,postpone:e.row.postpone,domain:e.row.domain}})},flatData:function(e){return new Promise(function(t){var a={};Object.keys(e).forEach(function(t){"object"==s(e[t])&&e[t]?"null"!==e[t]&&Object.keys(e[t]).forEach(function(n){a[t+"_"+n]=e[t][n]}):a[t]=e[t]}),t(a)})},changeSelect:function(e){var t=this;this.levelOptions=[],this.domainOptions.forEach(function(a){if(a.key==e)for(var n=0;n<a.value.length;n++)t.levelOptions.push({key:a.value[n],value:a.value[n]})})}},computed:o({},Object(n.e)("auth",{domainOptions:function(e){return e.domainOptions},certificate:function(e){return e.certificate}}),Object(n.e)("signup",{signList:function(e){return e.signList},page:function(e){return e.page}}))},r=(a("LalW"),a("KHd+")),u=Object(r.a)(i,function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"container"},[a("div",{staticClass:"queryInfo"},[a("el-row",{attrs:{gutter:40}},[a("el-col",{attrs:{span:4}},[a("p",[e._v("考生姓名：")]),a("el-input",{attrs:{placeholder:"考生姓名",clearable:""},model:{value:e.name,callback:function(t){e.name=t},expression:"name"}})],1),e._v(" "),a("el-col",{attrs:{span:4}},[a("p",[e._v("报考专业：")]),e._v(" "),a("el-select",{attrs:{placeholder:"全部"},on:{change:e.changeSelect},model:{value:e.domain,callback:function(t){e.domain=t},expression:"domain"}},e._l(e.domainOptions,function(e){return a("el-option",{key:e.key,attrs:{label:e.key,value:e.key}})}))],1),e._v(" "),a("el-col",{attrs:{span:4,offset:0}},[a("p",[e._v("报考级别：")]),e._v(" "),a("el-select",{attrs:{placeholder:"全部"},model:{value:e.level,callback:function(t){e.level=t},expression:"level"}},e._l(e.levelOptions,function(e){return a("el-option",{key:e.value,attrs:{label:e.value,value:e.value}})}))],1),e._v(" "),a("el-col",{attrs:{span:8}},[a("p",[e._v("报名时间：")]),e._v(" "),a("el-date-picker",{attrs:{type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期"},model:{value:e.signTime,callback:function(t){e.signTime=t},expression:"signTime"}})],1)],1),e._v(" "),a("el-row",{attrs:{gutter:40}},[a("el-col",{attrs:{span:4}},[a("p",[e._v("证件类型：")]),e._v(" "),a("el-select",{attrs:{placeholder:"全部"},model:{value:e.id_type,callback:function(t){e.id_type=t},expression:"id_type"}},e._l(e.idCardOptions,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1),e._v(" "),a("el-col",{attrs:{span:6}},[a("p",[e._v("证件号码：")]),e._v(" "),a("el-input",{attrs:{placeholder:"证件号码",clearable:""},model:{value:e.id_number,callback:function(t){e.id_number=t},expression:"id_number"}})],1),e._v(" "),a("el-col",{attrs:{span:5}},[a("p",[e._v("负责报名机构：")]),e._v(" "),a("el-input",{attrs:{placeholder:"报名机构",clearable:""},model:{value:e.organ_name,callback:function(t){e.organ_name=t},expression:"organ_name"}})],1),e._v(" "),a("el-col",{attrs:{span:5}},[a("p",[e._v("负责报名老师：")]),e._v(" "),a("el-input",{attrs:{placeholder:"老师姓名",clearable:""},model:{value:e.teacher_name,callback:function(t){e.teacher_name=t},expression:"teacher_name"}})],1)],1),e._v(" "),a("el-row",{attrs:{gutter:40}},[a("el-col",{attrs:{span:4}},[a("p",[e._v("审核状态：")]),e._v(" "),a("el-select",{attrs:{placeholder:"全部"},model:{value:e.status,callback:function(t){e.status=t},expression:"status"}},e._l(e.verifyOptions,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1),e._v(" "),a("el-col",{attrs:{span:4}},[a("p",[e._v("当前进度：")]),e._v(" "),a("el-select",{attrs:{placeholder:"全部"},model:{value:e.plan,callback:function(t){e.plan=t},expression:"plan"}},e._l(e.progressOptions,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1),e._v(" "),a("el-col",{attrs:{span:5}},[a("p",[e._v("是否存在缺考顺延：")]),e._v(" "),a("el-select",{attrs:{placeholder:"全部"},model:{value:e.postpone,callback:function(t){e.postpone=t},expression:"postpone"}},e._l(e.islostTestOptions,function(e){return a("el-option",{key:e.value,attrs:{label:e.label,value:e.value}})}))],1),e._v(" "),a("el-col",{attrs:{span:5}},[a("el-button",{attrs:{type:"primary"},on:{click:function(t){return t.preventDefault(),e.queryInfo(t)}}},[e._v("查询")])],1)],1)],1),e._v(" "),a("div",{staticClass:"download"},[a("el-button",{attrs:{type:"primary"}},[e._v("导出考级名单报名表")]),e._v(" "),a("el-button",{attrs:{type:"primary"}},[e._v("导出考级录入系统报名表")])],1),e._v(" "),a("el-container",{staticClass:"queryResult"},[a("table-data",{attrs:{isPagination:"true",totalNumber:e.page.total,currentPage:e.page.pn,pageSize:e.page.limit,head:e.head,tableData:e.signList,isOption:"true",isEditTable:"true",editTableName:"查看详情",isSelected:"'true'",loadingTable:e.isLoading},on:{editInfo:e.signDetail,handleCurrentChange:e.handleCurrentChange}})],1)],1)},[],!1,null,"26fb0022",null);t.default=u.exports}}]);