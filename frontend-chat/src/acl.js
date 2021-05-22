import Vue from 'vue'
import { AclInstaller, AclCreate, AclRule } from 'vue-acl'
import router from '@/router'
import encode from '@/utils/encode.native'
import Routes from '@/api/routes'

Vue.use(AclInstaller)

export default new AclCreate({
  initial: 'public',
  notfound: {
    path: '/notfound',
    forwardQueryParams: true
  },
  router,
  acceptLocalRules: false,
  globalRules: {
    isPublic: new AclRule('public').generate(),
    isEveryone: new AclRule('public').or('logged').generate(),
    isLogged: new AclRule('logged').generate()
  },
  middleware: async acl => {
    const routesAPI = new Routes()
    const user = await routesAPI.tryAuthJWT()
    if (user) {
      sessionStorage.setItem('user_logged', encode(user, true))
      acl.change('logged')
    }
  }
})
