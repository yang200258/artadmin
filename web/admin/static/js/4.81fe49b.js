(window.webpackJsonp=window.webpackJsonp||[]).push([[4],{HndG:function(e,a,r){"use strict";r.r(a);var o={data:function(){return{addForm:{organ_name:"",organ_area:"",organ_address:"",name:"",phone:[],username:"",password:""},districtOptions:[{key:"0",label:"海口市"},{key:"1",label:"其他市县"}],rules:{organ_name:[{required:!0,message:"请输入机构名称",trigger:"blur"}],organ_area:[{required:!0,message:"请输入所属地区",trigger:"change"}],organ_address:[{required:!0,message:"请输入机构地址",trigger:"blur"}],name:[{required:!0,message:"请输入机构联系人",trigger:"blur"}],phone1:[{required:!0,message:"请输入联系电话",trigger:"blur"}],username:[{required:!0,message:"请输入官网登录账号",trigger:"blur"}],password:[{required:!0,message:"请输入官网登录密码",trigger:"blur"}]}}},components:{},methods:{submitForm:function(e){var a=this,r=e.name,o=e.organ_address,n=e.organ_name,t=e.username,l=e.organ_area,s=e.password,d=e.phone;this.$refs.addOgan.validate(function(e){e&&a.$axios({url:"/manager/organ/add",method:"post",data:{name:r,organ_address:o,organ_name:n,username:t,organ_area:l,password:s,phone:d}}).then(function(e){e&&e.error,alert(e.msg),a.$router.push({name:"oganization"})}).catch(function(e){console.log(e)})})}}},n=(r("xSek"),r("KHd+")),t=Object(n.a)(o,function(){var e=this,a=e.$createElement,r=e._self._c||a;return r("div",{staticClass:"oganization-container"},[r("span",[e._v("机构信息")]),e._v(" "),r("el-form",{ref:"addOgan",attrs:{model:e.addForm,rules:e.rules}},[r("el-form-item",{attrs:{label:"机构名称：",prop:"organ_name"}},[r("el-row",[r("el-input",{attrs:{placeholder:"请输入姓名"},model:{value:e.addForm.organ_name,callback:function(a){e.$set(e.addForm,"organ_name",a)},expression:"addForm.organ_name"}})],1)],1),e._v(" "),r("el-form-item",{attrs:{label:"所属地区：",prop:"organ_area"}},[r("el-row",[r("el-select",{attrs:{placeholder:"请选择所属地区"},model:{value:e.addForm.organ_area,callback:function(a){e.$set(e.addForm,"organ_area",a)},expression:"addForm.organ_area"}},e._l(e.districtOptions,function(e){return r("el-option",{key:e.key,attrs:{label:e.label,value:e.key}})}))],1)],1),e._v(" "),r("el-form-item",{attrs:{label:"机构地址：",prop:"organ_address"}},[r("el-row",[r("el-input",{attrs:{placeholder:"请输入机构地址"},model:{value:e.addForm.organ_address,callback:function(a){e.$set(e.addForm,"organ_address",a)},expression:"addForm.organ_address"}})],1)],1),e._v(" "),r("el-form-item",{attrs:{label:"联系人：",prop:"name"}},[r("el-row",[r("el-input",{attrs:{placeholder:"请输入密码"},model:{value:e.addForm.name,callback:function(a){e.$set(e.addForm,"name",a)},expression:"addForm.name"}})],1)],1),e._v(" "),r("el-form-item",{attrs:{label:"联系电话1",prop:"phone1"}},[r("el-row",[r("el-input",{attrs:{placeholder:"请输入联系电话"},model:{value:e.addForm.phone[0],callback:function(a){e.$set(e.addForm.phone,0,a)},expression:"addForm.phone[0]"}})],1)],1),e._v(" "),r("el-form-item",{attrs:{label:"联系电话2",prop:"phone2"}},[r("el-row",[r("el-input",{attrs:{placeholder:"请输入联系电话"},model:{value:e.addForm.phone[1],callback:function(a){e.$set(e.addForm.phone,1,a)},expression:"addForm.phone[1]"}})],1)],1),e._v(" "),r("el-form-item",{attrs:{label:"联系电话3",prop:"phone3"}},[r("el-row",[r("el-input",{attrs:{placeholder:"请输入联系电话"},model:{value:e.addForm.phone[2],callback:function(a){e.$set(e.addForm.phone,2,a)},expression:"addForm.phone[2]"}})],1)],1),e._v(" "),r("el-form-item",{attrs:{label:"官网登录账号：",prop:"username"}},[r("el-row",[r("el-input",{attrs:{placeholder:""},model:{value:e.addForm.username,callback:function(a){e.$set(e.addForm,"username",a)},expression:"addForm.username"}})],1)],1),e._v(" "),r("el-form-item",{attrs:{label:"官网登录密码：",prop:"password"}},[r("el-row",[r("el-input",{attrs:{type:"password",placeholder:""},model:{value:e.addForm.password,callback:function(a){e.$set(e.addForm,"password",a)},expression:"addForm.password"}})],1)],1),e._v(" "),r("el-form-item",[r("el-row",[r("el-col",{attrs:{span:2,push:10}},[r("el-button",{attrs:{type:"primary"},on:{click:function(a){e.submitForm(e.addForm)}}},[e._v("保存修改")])],1)],1)],1)],1)],1)},[],!1,null,"428d819a",null);a.default=t.exports},LoRe:function(e,a,r){},xSek:function(e,a,r){"use strict";var o=r("LoRe");r.n(o).a}}]);