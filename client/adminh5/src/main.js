// 生产环境中注释掉以下语句
// import 'sysStatic/css/theme-default.scss'
// import '../mock/index.js'

import 'babel-polyfill'
import Vue from "vue"
import {Pagination,Dialog,Input,Radio,Checkbox,Select,Button,Option,Table,DatePicker,TimePicker,Form,FormItem,Icon,Upload,Container,Header,Aside,Main,Footer,TableColumn,
    Row,Col,Tag,Menu,Submenu,MenuItem,CheckboxGroup,RadioGroup,Dropdown,DropdownMenu,DropdownItem,Loading,Message,MessageBox} from 'element-ui'
Vue.use(Pagination)
Vue.use(Dialog)
Vue.use(TableColumn)
Vue.use(Input)
Vue.use(Radio)
Vue.use(Checkbox)
Vue.use(CheckboxGroup)
Vue.use(Select)
Vue.use(Button)
Vue.use(Option)
Vue.use(Table)
Vue.use(DatePicker)
Vue.use(TimePicker)
Vue.use(Main)
Vue.use(Aside)
Vue.use(Form)
Vue.use(FormItem)
Vue.use(Icon)
Vue.use(Upload)
Vue.use(Header)
Vue.use(Container)
Vue.use(Footer)
Vue.use(Row)
Vue.use(Col)
Vue.use(Tag)
Vue.use(Menu)
Vue.use(Submenu)
Vue.use(MenuItem)
Vue.use(RadioGroup)
Vue.use(Dropdown)
Vue.use(DropdownMenu)
Vue.use(DropdownItem)
Vue.use(Loading)
Vue.prototype.$message = Message
Vue.prototype.$MessageBox = MessageBox
// Vue.use(MessageBox)

import router from './router'
import store from './store'
import axios from './util/ajax'
import i18n from './util/i18n'
import App from './index'

import './components/install'
// import './plugins/install'

// 注册组件到Vue
Vue.prototype.$axios = axios
Vue.use( {
    i18n: (key, value) => i18n.t(key, value)
})

new Vue({
    i18n,
    axios,
    router,
    store,
    render: h => h(App)
}).$mount('#app')