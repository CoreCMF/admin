// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import {App, Store} from 'builder-vue-iview'
import VueRouter from 'vue-router'
import Axios from 'axios'
console.log(App)
Vue.use(VueRouter)

Vue.prototype.$http = Axios
Vue.config.productionTip = false
/* 设置api通信url */
Store.state.apiUrl = window.config.apiUrl
/* eslint-disable no-new */
const router = new VueRouter({
  routes: []
})
new Vue({
  el: '#app',
  router,
  Store,
  render: h => h(App)
})
