(window.webpackJsonp=window.webpackJsonp||[]).push([[15],{DgvE:function(e,t,o){"use strict";var a={delIdArray:function(e,t){for(var o=0;o<e.length;o++)e[o].id==t&&e.splice(o,1);console.log("this.arr",e)},filterDate:function(e){return e?e.getFullYear()+"-"+(e.getMonth()+1>=10?e.getMonth()+1:"0"+(e.getMonth()+1))+"-"+(e.getDate()>=10?e.getDate():"0"+e.getDate()):""},filterDateTime:function(e){return e?e.getFullYear()+"-"+(e.getMonth()+1>=10?e.getMonth()+1:"0"+(e.getMonth()+1))+"-"+(e.getDate()>=10?e.getDate():"0"+e.getDate())+" "+(e.getHours()>=10?e.getHours():"0"+e.getHours())+":"+(e.getMinutes()>=10?e.getMinutes():"0"+e.getMinutes())+":"+(e.getSeconds()>=10?e.getSeconds():"0"+e.getSeconds()):""}};t.a=a},Yfzn:function(e,t,o){},bTzI:function(e,t,o){"use strict";o.r(t);var a=o("pDsv"),n=o("DgvE"),i={data:function(){return{head:[{key:"uid",name:"编号"},{key:"name",name:"考生姓名"},{key:"domain",name:"报考专业"},{key:"level",name:"报考级别"},{key:"create_at",name:"报名时间"}],addInformData:[],editName:"添加通知对象",loadingInformTable:!1,loadingAddInformTable:!1,deleteTableName:"删除",editType:"",dialogVisible:!1,name:"",domain:[],level:"",levelOptions:[],signTime:[],currentPage:1,pageSize:50,totalNumber:0,loadingTable:!1,informHead:[{key:"uid",name:"编号"},{key:"name",name:"考生姓名"},{key:"domain",name:"报考专业"},{key:"level",name:"报考级别"},{key:"create_at",name:"报名时间"}],uid_arr:[],readyinforobject:[]}},components:{tableData:a.a},mounted:function(){this.$route.params.inform&&(this.informobject=this.$route.params.inform)},methods:{chooseInformObject:function(e){var t=this,o=this.name,a=this.domain,i=this.level;e=e||1;var r=n.a.filterDate(this.signTime[0])||"",l=n.a.filterDate(this.signTime[1])||"";this.$axios({url:"/inform/object",method:"post",data:{name:o,domain:a,level:i,start_time:r,end_time:l,pn:e}}).then(function(e){console.log("获取通知对象列表数据",e),e&&!e.error&&(t.addInformData=e.data.list,t.totalNumber=e.data.page.total,t.pageSize=e.data.page.limit,t.currentPage=e.data.page.pn)}).catch(function(e){console.log(e)})},cancel:function(){var e=this;this.dialogVisible=!1,this.$store.commit("informobject/setaddInformobject",this.informobject),console.log("*******************",this.informobject),this.uid_arr=[],this.informobject.forEach(function(t){e.uid_arr.push(t.uid)}),this.$router.push({name:"publishInform",params:{uid_arr:this.uid_arr,type:this.type,inform:this.informobject}})},handleCurrentChange:function(e){this.chooseInformObject(e)},handleSelectionChange:function(e){console.log(e),this.readyinforobject=e},saveAddInform:function(){var e=this;this.$store.commit("informobject/setaddInformobject",this.readyinforobject),this.dialogVisible=!1,this.uid_arr=[],this.readyinforobject.forEach(function(t){e.uid_arr.push(t.uid)}),this.$router.push({name:"publishInform",params:{uid_arr:this.uid_arr,type:this.type,inform:this.readyinforobject}})},deleteInfo:function(e){var t=this,o=this.$route.params.inform_id||"",a=this.$store.state.informobject.informobjectdata,n=[];if(console.log(o),o){var i=e.row.uid;this.$axios({url:"/inform/object/delete",method:"post",data:{uid:i,inform_id:o}}).then(function(e){console.log("删除通知对象响应",e),e&&!e.error&&(a.forEach(function(e){e.uid!==i&&n.push(e)}),t.$store.commit("informobject/setInformobject",n))}).catch(function(e){console.log(e)})}},getOption:function(){var e=this;this.$axios({url:"/option",method:"post",data:{}}).then(function(t){if(console.log("获取基本选项",t),t&&!t.error){var o=t.data.major,a=[];for(var n in o)a.push({value:n,text:n,levels:o[n]});e.options=a,console.log("majors",a)}}).catch(function(e){console.log(e)})},editOption:function(){this.dialogVisible=!0,this.getOption()},close:function(){var e=this;this.dialogVisible=!1,this.$store.commit("informobject/setaddInformobject",this.informobject),console.log("*******************",this.informobject),this.uid_arr=[],this.informobject.forEach(function(t){e.uid_arr.push(t.uid)}),this.$router.push({name:"publishInform",params:{uid_arr:this.uid_arr,type:this.type,inform:this.informobject}})},changeSelect:function(e){var t=this;this.levelOptions=[],this.domainOptions.forEach(function(o){if(o.key==e)for(var a=0;a<o.value.length;a++)t.levelOptions.push({key:o.value[a],value:o.value[a]})})},getRowKey:function(e){return e.uid}},computed:{informobject:function(){return this.$store.state.informobject.addinformobjectdata},type:function(){return this.$store.state.informobject.type},domainOptions:function(){return this.$store.state.auth.domainOptions}}},r=(o("lXn0"),o("KHd+")),l=Object(r.a)(i,function(){var e=this,t=e.$createElement,o=e._self._c||t;return o("div",{staticClass:"inform-object"},[o("table-data",{attrs:{head:e.head,tableData:e.informobject,addInformData:e.addInformData,isEdit:"'true'",editName:e.editName,isDeleteTable:"true",deleteTableName:e.deleteTableName,editType:e.editType,loadingTable:e.loadingInformTable,loadingAddInformTable:e.loadingAddInformTable},on:{editOption:e.editOption,deleteInfo:e.deleteInfo,saveAddInform:e.saveAddInform,close:e.close,getRowKey:e.getRowKey}}),e._v(" "),o("el-dialog",{staticClass:"dialog-container",attrs:{title:"添加通知对象","before-close":e.close,visible:e.dialogVisible,width:"60%",center:!0,"lock-scroll":!1},on:{"update:visible":function(t){e.dialogVisible=t}}},[o("div",{staticClass:"inform-header"},[o("el-row",{attrs:{gutter:40}},[o("el-col",{attrs:{span:8}},[o("p",[e._v("考生姓名：")]),o("el-input",{attrs:{placeholder:"考生姓名",clearable:""},model:{value:e.name,callback:function(t){e.name=t},expression:"name"}})],1),e._v(" "),o("el-col",{attrs:{span:8}},[o("p",[e._v("报考专业：")]),e._v(" "),o("el-select",{attrs:{placeholder:"全部"},on:{change:e.changeSelect},model:{value:e.domain,callback:function(t){e.domain=t},expression:"domain"}},e._l(e.domainOptions,function(e){return o("el-option",{key:e.key,attrs:{label:e.key,value:e.key}})}))],1),e._v(" "),o("el-col",{attrs:{span:8,offset:0}},[o("p",[e._v("报考级别：")]),e._v(" "),o("el-select",{attrs:{placeholder:"全部"},model:{value:e.level,callback:function(t){e.level=t},expression:"level"}},e._l(e.levelOptions,function(e){return o("el-option",{key:e.value,attrs:{label:e.value,value:e.value}})}))],1)],1),e._v(" "),o("el-row",{attrs:{gutter:40}},[o("el-col",{attrs:{span:8}},[o("p",[e._v("报名时间：")]),e._v(" "),o("el-date-picker",{attrs:{type:"daterange","range-separator":"至","start-placeholder":"开始日期","end-placeholder":"结束日期"},model:{value:e.signTime,callback:function(t){e.signTime=t},expression:"signTime"}})],1),e._v(" "),o("el-col",{attrs:{span:4,offset:2}},[o("el-button",{attrs:{type:"primary"},on:{click:function(t){return t.preventDefault(),e.chooseInformObject(t)}}},[e._v("筛选")])],1)],1)],1),e._v(" "),o("table-data",{attrs:{isPagination:"true",currentPage:e.currentPage,pageSize:e.pageSize,totalNumber:e.totalNumber,isSelected:"true",tableData:e.addInformData,head:e.informHead,loadingTable:e.loadingAddInformTable},on:{handleSelectionChange:e.handleSelectionChange}}),e._v(" "),o("span",{attrs:{slot:"footer"},slot:"footer"},[o("el-button",{on:{click:e.cancel}},[e._v("取消")]),e._v(" "),o("el-button",{on:{click:e.saveAddInform}},[e._v("保存")])],1)],1)],1)},[],!1,null,"1b898809",null);t.default=l.exports},lXn0:function(e,t,o){"use strict";var a=o("Yfzn");o.n(a).a}}]);