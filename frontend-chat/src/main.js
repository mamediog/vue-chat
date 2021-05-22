import Vue from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import acl from '@/acl'

/**
 * Global Utils
 */
import '@/utils/index'

Vue.config.productionTip = false
Vue.prototype.$bus = new Vue()

new Vue({
  router,
  acl,
  render: h => h(App)
}).$mount('#app')
