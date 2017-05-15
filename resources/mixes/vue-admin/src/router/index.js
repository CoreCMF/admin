import Vue from 'vue'
import Router from 'vue-router'
import axios from 'axios'

import Hello from '@/components/Hello'

console.log(axios)
axios.post(window.CoreCmf.apiUrl)
console.log('demo')
Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Hello',
      component: Hello
    }
  ]
})
