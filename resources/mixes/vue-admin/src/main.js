// 引入依赖
import builderVue from 'builder-vue'
// 引用
import BuilderVueElement from 'builder-vue-element'
import ContainerVueElement from 'container-vue-element'
builderVue.Vue.use(BuilderVueElement)
builderVue.Vue.use(ContainerVueElement)
// 启动自动构建
builderVue.start()
