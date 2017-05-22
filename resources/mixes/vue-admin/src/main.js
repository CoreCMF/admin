import Vue from 'vue'
import {App, router, store} from 'builder-vue'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import builderVueElement from 'builder-vue-element'
import Layout from './components/Layout.vue'
import Axios from 'axios'

Vue.use(ElementUI)
Vue.use(builderVueElement)
Vue.prototype.$http = Axios

/* 设置api通信url */
store.state.apiUrl = window.config.apiUrl
/* 容器组件 */
store.state.container = Layout
/* builder索引组件 */
store.state.builderIndex = { template: '<Bve-index/>' }

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
