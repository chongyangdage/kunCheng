import Vue from 'vue'
import App from './App'

Vue.config.productionTip = false

App.mpType = 'app'
import {myRequest,myUploadFile} from './utils/myUploadFile.js'; //接口挂载原型链

Vue.prototype.$myRequest = myRequest //请求

Vue.prototype.$myUploadFile = myUploadFile //上传
const app = new Vue({
    ...App
})
app.$mount()
