<template>
  <header class="chat-header" v-if="user">
    <div class="chat-header__user">
      <img :src="user.image">

      <div class="chat-header__settings">
        <span class="far fa-comment-alt"></span>
        <span class="fas fa-ellipsis-v"></span>
      </div>
    </div>

    <div class="chat-header__friends">
      <nav class="chat-header__friends-search">
        <div class="chat-header__search-input">
          <span class="fas fa-search"></span>
          <input type="text" placeholder="Search or start a new chat">
        </div>
      </nav>

      <main class="chat-header__conversation">
        <div class="chat-header__conversation-friend">
          <img :src="`${baseURL}img/chat.png`" alt="Samara Florind">
          <article class="chat-header__conversation-info">
            <h2>Amor</h2>
            <p><span class="fas fa-check-double"></span> alguma mensagem aqui</p>
          </article>
        </div>

        <div class="chat-header__conversation-friend">
          <img :src="`${baseURL}img/chat.png`" alt="Samara Florind">
          <article class="chat-header__conversation-info">
            <h2>Amor</h2>
            <p><span class="fas fa-check-double"></span> alguma mensagem aqui</p>
          </article>
        </div>
      </main>
    </div>
  </header>
</template>

<script>
// MIXINS
import VerifyLogin from '@/utils/auth/setUserLogin'
import basePath from '@/utils/baseURL'

export default {
  mixins: [VerifyLogin, basePath],
  data: () => ({
    user: null
  }),
  computed: {
    baseURL () {
      return basePath.path
    }
  },
  async created () {
    this.user = await this.isLogged()
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
    img
      width: 50px
      border-radius: 50%
    .chat-header__settings
      width: 50px
      display: flex
      flex-flow: row
      align-items: center
      justify-content: space-between
      span
        color: $iconColor
        cursor: pointer
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
          border-radius: 30px
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
      height: 100%
      width: 100%
      .chat-header__conversation-friend
        width: calc(100% - 40px)
        padding: 20px
        display: flex
        flex-flow: row
        justify-content: flex-start
        align-items: center
        cursor: pointer
        transition: all .5s
        &:hover
          background: $borderLightColor
          transition: all .5s
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
          border-bottom: 1px solid $borderLightColor
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
</style>
