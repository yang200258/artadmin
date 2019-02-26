import Vue from 'vue'
import main from './Toast.vue'

let Main = Vue.extend(main)
let timer = null
let last = null

let Toast = (text, duration, callback) => {
  clearTimeout(timer)
  if (last) {
    let _last = window.document.getElementById(last)
    let _wrapper = _last.parentNode
    _wrapper.removeChild(_last)
  }
  let div = window.document.createElement('div')
  window.document.body.appendChild(div)
  let _main = new Main()
  let id = 'toast_' + new Date().getTime()
  last = id
  _main.id = id
  _main.text = text
  _main.$mount(div)
  timer = setTimeout(() => {
    Toast.hide(id)
    callback && callback()
  }, duration || 2000)
}

Toast.hide = (id) => {
  if (!last) {
    return false
  }
  let _toast = window.document.getElementById(id)
  let _wrapper = _toast.parentNode
  _wrapper.removeChild(_toast)
  last = null
}

export default Toast
