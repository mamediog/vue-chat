import core from './core'
import encode from '@/utils/encode.native'
import decode from '@/utils/decode.native'

class User {
  setContactData (data) {
    sessionStorage.setItem('contact_data', encode(data, true))
  }

  getContactData () {
    const data = sessionStorage.getItem('contact_data')
    if (data === null) return null
    return decode(data, true)
  }

  clearContactData () {
    sessionStorage.removeItem('contact_data')
  }

  clearStorageData () {
    this.clearContactData()
  }

  async register (data, token) {
    const contactData = this.getContactData()
    if ([contactData].includes(null)) throw new Error('empty-data')

    data = { ...data, ...contactData, token }
    return (await core().post('/auth/register', data)).data
  }

  async hasUser (email) {
    return (await core().get('/auth/hasuser/', { params: { email: email } })).data
  }

  async login (data) {
    return (await core().post('/auth/login', data)).data
  }

  async getEmailByToken (token) {
    return (await core().get(`/auth/verify/${token}`)).data.email
  }

  async tryAuthJWT () {
    try {
      const token = localStorage.getItem('user_token')
      if (token !== null) {
        return (await core().get('/auth/verify')).data.user
      }

      return false
    } catch (error) {
      return false
    }
  }
}

export default User
