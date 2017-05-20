// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import {App, router, store} from 'builder-vue'
import builderVueIview from 'builder-vue-iview'
import Axios from 'axios'

Vue.use(builderVueIview)

Vue.config.productionTip = false
Vue.prototype.$http = Axios
/* 设置api通信url */
store.state.apiUrl = window.config.apiUrl
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
