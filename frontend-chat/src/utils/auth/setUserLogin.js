import Routes from '@/api/routes'

export default {
  data: () => ({
    user: null
  }),
  created () {
    this.user = new Routes()
  },
  methods: {
    async isLogged () {
      var currentUser = await this.user.tryAuthJWT()
      return currentUser
    }
  }
}
