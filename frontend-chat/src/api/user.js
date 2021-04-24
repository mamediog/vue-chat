import core from './core'
// import encode from '@/utils/encode.native'
// import decode from '@/utils/decode.native'

class User {
  /**
   *
   * Auth requests
   */
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

  async logout () {
    return (await core().post('/auth/logout')).data
  }

  async tryAuthJWT () {
    try {
      const token = localStorage.getItem('user_token')
      if (token !== null) {
        return (await core().get('/auth/islogged')).data.user
      }
      return false
    } catch (error) {
      return false
    }
  }

  /**
   * User requests
   */
  async searchUsers (searchValue) {
    return (await core().post('/user/search', { search: searchValue })).data
  }

  async findFriends (id) {
    return (await core().get('/user/findfriends/' + id)).data
  }

  async addFriend (friendId, id) {
    return (await core().post('/user/addfriend/' + id, { friend_id: friendId })).data
  }

  async searchUser (id) {
    return (await core().get('/user/findfriends/searchuser/' + id)).data
  }

  async allUsers () {
    return (await core().get('/users')).data
  }

  /**
   * Chat requests
   */
  async initChat (friend) {
    return (await core().get('/chat/' + friend)).data
  }

  async getChats () {
    return (await core().get('/chat/all')).data
  }

  async sendMessage (chatId, messages) {
    return (await core().post('/chat/message/' + chatId, { messages: messages })).data
  }
}

export default User
