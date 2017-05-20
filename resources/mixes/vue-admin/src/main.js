// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import {App, router, store} from 'builder-vue'
import Axios from 'axios'
console.log(router)
Vue.prototype.$http = Axios
Vue.config.productionTip = false
/* 设置api通信url */
store.state.apiUrl = window.config.apiUrl
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
