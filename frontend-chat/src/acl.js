import Vue from 'vue'
import { AclInstaller, AclCreate, AclRule } from 'vue-acl'
import router from '@/router'
import encode from '@/utils/encode.native'
import User from '@/api/user'

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
    const userAPI = new User()
    const user = await userAPI.tryAuthJWT()
    if (user) {
      console.log('Manoo entrou aqui porra')
      sessionStorage.setItem('user_logged', encode(user, true))
      acl.change('logged')
    }
  }
})
