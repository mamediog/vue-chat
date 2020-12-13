<template>
  <header class="chat-header" v-if="user">
    <div class="chat-header__user">
      <img :src="user.image">

      <div class="chat-header__settings">
        <nav class="far fa-comment-alt"></nav>

        <nav class="fas fa-ellipsis-v" onclick="window.custom.openAndHideItem(this, 'chat-header__settings-ul--active', 0)">
          <ul class="chat-header__settings-ul">
            <li @click="logout">Logout</li>
            <li>Perfil</li>
            <li>Configurações</li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="chat-header__friends">
      <nav class="chat-header__friends-search">
        <div class="chat-header__search-input">
          <span class="fas fa-search"></span>
          <input type="text" v-model="search" @keyup="searchData(search)" placeholder="Search or start a new chat">
        </div>
      </nav>

      <main class="chat-header__conversation" id="chat-header__conversation">
        <!-- Resultados -->
        <section class="chat-header__conversation-friend-container" v-if="resultSearch.length > 0">
          <article class="chat-header__conversation-friend"
          onclick="window.custom.changeActiveChat(this)"
          v-for="(guy, gid) in resultSearch" :key="gid">
            <div v-if="guy._id !== user._id" class="chat-header__conversation-info-row">
              <img :src="guy.image" alt="User">

              <div class="chat-header__conversation-info">
                <h2>{{ guy.name }}</h2>
                <p><span class="fas fa-check-double"></span> alguma mensagem aqui</p>
              </div>
              <span
                class="fas fa-user-plus"
                @click="addFriend(guy._id)"
                v-if="!(user.friend || []).includes(guy._id)">
              </span>
            </div>
          </article>
        </section>

        <!-- Amigos -->
        <section class="chat-header__conversation-friend-container" v-if="resultSearch.length <= 0">
          <article class="chat-header__conversation-friend"
          onclick="window.custom.changeActiveChat(this)"
          v-for="(friend, fid) in friends" :key="fid">
            <div v-if="friend._id !== user._id" class="chat-header__conversation-info-row">
              <img v-if="friend.image !== undefined" :src="friend.image" alt="User">

              <div class="chat-header__conversation-info">
                <h2>{{ friend.name }}</h2>
                <p><span class="fas fa-check-double"></span> alguma mensagem aqui</p>
              </div>
            </div>
          </article>
        </section>
      </main>
    </div>
  </header>
</template>

<script>
import User from '@/api/user'

// MIXINS
import VerifyLogin from '@/utils/auth/setUserLogin'
import basePath from '@/utils/baseURL'

export default {
  mixins: [VerifyLogin, basePath],
  data: () => ({
    search: null,
    user: null,
    userAPI: null,
    resultSearch: [],
    friends: []
  }),
  computed: {
    baseURL () {
      return basePath.path
    }
  },
  async created () {
    this.userAPI = new User()
    this.user = await this.isLogged()
    this.findFriends()
  },
  methods: {
    changeActiveChat (elem) {
      console.log(elem)
    },
    async logout () {
      try {
        await this.userAPI.logout()
        localStorage.setItem('user_token', '')
        this.$router.push('/verify')
      } catch (error) {
        console.log(error.response)
      }
    },
    async findFriends () {
      try {
        var response = await this.userAPI.findFriends(this.user._id)
        this.friends = response
      } catch (error) {
        console.log(error.response)
      }
    },
    async addFriend (friendId) {
      try {
        var response = await this.userAPI.addFriend(friendId, this.user._id)

        this.search = ''

        setTimeout(() => {
          this.resultSearch = []
          this.findFriends()
          this.cleanSelectedUser()
        }, 1000)

        this.$bus.$emit('show-notify', response.message)
      } catch (error) {
        console.log(error.response)
      }
    },
    async searchData () {
      try {
        var response = await this.userAPI.searchUsers(this.search)
        this.resultSearch = response
      } catch (error) {
        console.log(error.response)
      }
    },
    cleanSelectedUser () {
      const elements = document.getElementById('chat-header__conversation').children[0].children
      console.log(elements)
      for (var i = 0; i < elements.length; i++) {
        if (elements[i]) {
          elements[i].classList.remove('chat-header__conversation-friend--active')
        }
      }
    }
  }
}
</script>

<style lang="sass" scoped>
@import '@/sass/style.sass'

.chat-header
  width: 400px
  height: 100%
  border-right: 1px solid $borderLightColor
  .chat-header__user
    width: calc(100% - 40px)
    padding: 10px 20px
    display: flex
    flex-flow: row
    justify-content: space-between
    align-items: center
    background: #ececec
    border-top-left-radius: $radius
    border-right: 1px solid #e0e0e0
    img
      width: 50px
      border-radius: 50%
    .chat-header__settings
      width: 70px
      display: flex
      flex-flow: row
      align-items: center
      justify-content: space-between
      nav
        color: $iconColor
        cursor: pointer
        width: 20px
        position: relative
        .chat-header__settings-ul
          position: absolute
          width: 0px
          background: #fff
          box-shadow: 0 0 10px rgba(0,0,0,0.175)
          z-index: 10
          border-radius: $radius
          right: 0
          top: 10px
          visibility: hidden
          transition: all .2s
          padding: 0
          display: flex
          flex-flow: column
          justify-content: flex-start
          align-content: flex-star
          li
            font-size: 0
            transition: all .2s
            list-style: none

        .chat-header__settings-ul--active
          transition: all .2s
          width: 150px !important
          visibility: visible !important
          li
            font-size: 14px !important
            transition: all .2s
            text-align: left
            width: calc(100% - 30px)
            padding: 15px
            color: #444
            font-weight: 400

  .chat-header__friends
    width: 100%
    display: flex
    flex-flow: column
    justify-content: flex-start
    align-content: flex-start
    .chat-header__friends-search
      width: 100%
      background: #f8f8f8
      height: 55px
      display: flex
      justify-content: center
      align-items: center
      .chat-header__search-input
        width: 100%
        position: relative
        display: flex
        flex-flow: row
        align-items: center
        justify-content: center
        input
          width: calc(90% - 40px)
          border-radius: $radius
          border: none
          height: 25px
          padding: 5px 0px
          padding-left: 40px
          color: $inputTextColor
        span
          color: $iconColor
          position: absolute
          align-self: center
          left: 33px
          font-size: 12px !important
    .chat-header__conversation
      width: 100%
      .chat-header__conversation-friend-container
        width: 100%
        height: calc((90vh - 55px) - 70px)
        display: flex
        flex-flow: column
        justify-content: flex-start
        align-items: center
        z-index:9
        overflow-x: hidden
        overflow-y: auto
        .chat-header__conversation-friend--active
          margin-left: 0px
          box-shadow: 0px 0 10px 0 rgba(0, 140, 0, 0.3)
          border-left: 3px solid $primaryColor
          transform: translateX(10px)
          background: $borderLightColor !important
        .chat-header__conversation-friend
          width: calc(97% - 40px)
          padding: 10px 20px
          display: flex
          flex-flow: row
          justify-content: flex-start
          align-items: center
          cursor: pointer
          transition: all .5s
          border-radius: $radius
          margin-top: 5px
          box-shadow: 0 2px 7px 0 rgba(0,0,0,0.1)
          &:hover
            background: $borderLightColor
            transition: all .5s
          &:last-child
            margin-bottom: 5px
          .chat-header__conversation-info-row
            width: 100%
            display: flex
            flex-flow: row
            position: relative
            img
              width: 55px
              border-radius: 50%
            .chat-header__conversation-info
              height: 100%
              width: 100%
              display: flex
              flex-flow: column
              justify-content: center
              align-items: flex-start
              margin-left: 10px
              padding-bottom: 10px
              *
                margin: 0
              h2
                font-size: 16px
                font-weight: 400
                margin-bottom: 7px
              p
                font-size: 14px
                color: $inputTextColor
                span
                  font-size: 10px
                  color: $readMessageColor
                  margin-right: 5px
            .fa-user-plus
              position: absolute
              top: 0px
              right: 0px
              color: $primaryColor
</style>
