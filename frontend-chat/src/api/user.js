import core from './core'
// import encode from '@/utils/encode.native'
// import decode from '@/utils/decode.native'

class User {
  async register (data) {
    data = { ...data }
    return (await core().post('/auth/register', data)).data
  }

  async hasUser (email) {
    return (await core().get('/auth/hasuser/', { params: { email: email } })).data
  }

  async login (data) {
    return (await core().post('/auth/login', data)).data
  }

  async allUsers () {
    return (await core().get('/users')).data
  }

  async tryAuthJWT () {
    try {
      const token = localStorage.getItem('user_token')
      if (token !== null) {
        return (await core().get('/islogged')).data.user
      }
      return false
    } catch (error) {
      return false
    }
  }
}

export default User
