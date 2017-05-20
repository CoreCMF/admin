import Vue from 'vue'
import {App, router, store} from 'builder-vue'
import iView from 'iview'
import 'iview/dist/styles/iview.css'
import BuilderVueIview from 'builder-vue-iview'
import Index from './components/index.vue'
import Axios from 'axios'

Vue.use(iView)
Vue.use(BuilderVueIview)
Vue.prototype.$http = Axios

/* 设置api通信url */
store.state.apiUrl = window.config.apiUrl
/* 容器组件 */
store.state.container = Index
/* builder索引组件 */
store.state.builderIndex = { template: '<Bvi-index></Bvi-index>' }

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
