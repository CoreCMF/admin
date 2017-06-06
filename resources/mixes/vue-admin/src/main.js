import Vue from 'vue'
import {App, router, store} from 'builder-vue'
import ElementUI from 'element-ui'
import BuilderVueElement from 'builder-vue-element'
import ContainerVueElement from 'container-vue-element'
window.axios = require('axios')

Vue.use(ElementUI)
Vue.use(BuilderVueElement)
Vue.use(ContainerVueElement)

/* 设置api通信url */
store.state.apiUrl = window.config.apiUrl
/* 容器组件 */
store.state.container = { template: '<cve-layout/>' }
/* builder索引组件 */
store.state.builderIndex = { template: '<bve-index/>' }

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
