import Vue from 'vue'
import {App, router, store} from 'builder-vue'
import builderVueIview from 'builder-vue-iview'
import Axios from 'axios'

Vue.use(builderVueIview)
Vue.prototype.$http = Axios

/* 设置api通信url */
store.state.apiUrl = window.config.apiUrl
/* 容器组件 */
store.state.container = ''
/* builder索引组件 */
store.state.builderIndex = '<Bvi-index></Bvi-index>'

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
