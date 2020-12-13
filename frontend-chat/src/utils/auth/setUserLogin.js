import User from '@/api/user'

export default {
  data: () => ({
    user: null
  }),
  created () {
    this.user = new User()
  },
  methods: {
    async isLogged () {
      var currentUser = await this.user.tryAuthJWT()
      return currentUser
    }
  }
}
