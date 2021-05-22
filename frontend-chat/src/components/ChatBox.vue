<template>
  <div class="chat-box">
    <div v-if="chat.friend">
      <header class="chat-box__header">
        <section class="chat-box__header-user">
          <img :src="(chat.friend || {}).image" alt="Friend">
          <h2>{{ (chat.friend || {}).name }}</h2>
        </section>

        <nav class="chat-box__header-options">
          <ul class="chat-box__header-options-ul">
            <li><span class="fas fa-paperclip"></span></li>
          </ul>
        </nav>
      </header>

      <article class="chat-box__content"
        :style="{ 'background-image': `url(${baseURL}img/background-chat.png)` }">
        <section class="chat-box__conversation">
          <div v-for="(message, mid) in chat.messages" :key="mid"
            :class="{ 'chat-box__message': true, 'chat-box__message-receive': message.userId !== user._id}">
            <p class="chat-box__message-text">{{  (message || {}).message }} <small class="chat-box__time"> {{ (message || {}).date }}</small></p>
          </div>
        </section>
      </article>

      <section class="chat-box__form">
        <textarea type="text" v-model="message" @keyup.enter="sendMessage((message || {}))" placeholder="Type a message"></textarea>
        <button @click="sendMessage((message || {}))"><span class="fas fa-chevron-right"></span></button>
      </section>
    </div>

    <div v-else class="chat-box__empty">
      <img alt="Vue Chat" :src="`${baseURL}img/icons/logo.png`" class="chat-logo">
      <h1>Inicie uma conversa</h1>
      <p>Projeto open source usando Vuejs + Lumen</p>
      <small>Não é permitido a venda! Apenas por motivos educacionais.</small>
    </div>
  </div>
</template>

<script>
import basePath from '@/utils/baseURL'
import Routes from '@/api/routes'

// MIXINS
import VerifyLogin from '@/utils/auth/setUserLogin'

export default {
  data: () => ({
    chat: {},
    message: '',
    ws: null
  }),
  mixins: [VerifyLogin],
  computed: {
    baseURL () {
      return basePath.path
    }
  },
  async created () {
    this.routesAPI = new Routes()
    this.user = await this.isLogged()

    this.$bus.$on('get-chat', this.getChat)
    this.connect()
  },
  destroyed () {
    this.$bus.$off('get-chat', this.getChat)
  },

  methods: {
    /**
     * When open a chat, the getChat is called to set a new chat in ChatBox
     *
     * @param {object} chat
     * @return void
     */
    getChat (chat) {
      this.chat = chat
      setTimeout(() => {
        this.gotoBottom('.chat-box__conversation')
      }, 300)
    },

    /**
     * Open Connection with websocket
     *
     * @return void
     */
    connect () {
      var self = this

      self.ws = new WebSocket('ws://localhost:8090')

      self.ws.onerror = function () {
        self.addErrorNotification('Não foi possível conectar-se ao servidor')
      }

      self.ws.onmessage = function (e) {
        self.getOnMessage(JSON.parse(e.data))
      }
    },

    /**
     * Set message in messages array received from web socket
     *
     * @param {object} data
     * @return void
     */
    getOnMessage (data) {
      if ((this.chat || {}).messages) {
        this.chat.messages.push(data.message)
        setTimeout(() => {
          this.gotoBottom('.chat-box__conversation')
        }, 300)
      }
    },

    /**
     * Send a message
     *
     * @param {string} message
     * @return void
     */
    async sendMessage (message) {
      var self = this
      var dateNow = new Date()
      var dateFormat = this.$$dateFormatBR(dateNow)
      var lastMessage = {}

      if (message !== '') {
        lastMessage = {
          user: this.user.name,
          userId: this.user._id,
          date: dateFormat.replace(' ', ', '),
          message: message
        }

        this.chat.messages.push(lastMessage)
        this.message = ''

        setTimeout(() => {
          this.gotoBottom('.chat-box__conversation')
        }, 300)

        self.ws.send(
          JSON.stringify({
            chat: this.chat._id,
            message: lastMessage
          })
        )

        this.routesAPI.sendMessage(this.chat._id, lastMessage)
      } else {
        alert('Digite alguma mensagem..')
      }
    },

    /**
     * Get element by selector, and set scroll to bottom "0"
     * getting the max height and setting on scrollTop
     *
     * @param {string} el
     * @return void
     */
    gotoBottom (el) {
      var elem = document.querySelector(el)
      elem.scrollTop = elem.scrollHeight
    }
  }
}
</script>

<style lang="sass" scoped>
@import '@/sass/style.sass'
.chat-box__empty
  height: calc(100vh - 96px)
  display: flex
  flex-flow: column
  justify-content: center
  align-items: center
  user-select: none
  h1
    font-weight: 400
    margin-top: 20px
  p
    margin-top: 20px
  small
    margin-top: 10px
  img
    width: 100px

.chat-box
  width: calc(100% - 400px)
  display: flex
  flex-flow: column
  position: relative
  .chat-box__header
    width: calc(100% - 40px)
    padding: 10px 20px
    height: 50px
    display: flex
    flex-flow: row
    justify-content: space-between
    align-items: center
    background: #ececec
    border-top-right-radius: $radius
    box-shadow: 10px 1px 10px 0 rgba(0, 0, 0, 0.22)
    z-index: 1
    .chat-box__header-user
      display: flex
      flex-flow: row
      justify-content: flex-start
      align-items: center
      img
        width: 50px
        height: 50px
        object-fit: cover
        border-radius: 50%
      h2
        font-size: 16px
        margin-left: 10px
        font-weight: 600
    .chat-box__header-options
      margin-right: 20px
      .chat-box__header-options-ul
        li
          list-style: none
          cursor: pointer
          span
            font-size: 20px
            color: $iconColor
  .chat-box__content
    width: 100%
    height: calc(90vh - 70px)
    background-repeat: no-repeat
    background-size: 100%
    background-color: #fff
    display: flex
    justify-content: flex-start
    align-items: flex-start
    .chat-box__conversation
      width: 100%
      height: calc(100% - 80px)
      flex-flow: column-reverse
      overflow-y: auto
      .chat-box__message
        width: calc(100% - 40px)
        padding: 0 20px
        align-self: flex-end
        display: flex
        justify-content: flex-end
        .chat-box__message-text
          align-self: flex-end
          max-width: 60%
          background-color: $messageBackgroundColor
          padding: 10px
          border-radius: $radius
          border-top-right-radius: 0
          color: $messageTextColor
          position: relative
          font-size: 15px
          margin: 5px 0
          box-shadow: 0 0 10px rgba(0,0,0,0.2)
          text-align: left
          &::after
            content: ''
            position: absolute
            top: 0
            right: -10px
            width: 0
            border: 10px solid
            border-color: $messageBackgroundColor transparent transparent transparent
          .chat-box__time
            font-size: 9px
            padding-left: 20px
            color: #787878
      .chat-box__message-receive
        justify-content: flex-start
        .chat-box__message-text
          background-color: #fff
          border-top-left-radius: 0
          &::after
            content: ''
            right: unset
            left: -10px
            width: 0
            border-color: #fff transparent transparent transparent
  .chat-box__form
    position: absolute
    bottom: 20px
    width: 100%
    display: flex
    justify-content: center
    align-items: center
    textarea
      width: calc(80% - 20px)
      height: calc(50px - 20px)
      padding: 10px
      border: 1px solid #ccc
      border-radius: $radius
      box-shadow: 0 0 10px 0 rgba(0,0,0,0.2)
      color: $inputTextColor
      &::placeholder
        color: $iconColor
    button
      width: 50px
      height: 50px
      border-radius: $radius
      border: none
      background: $primaryColor
      margin-left: 10px
      cursor: pointer
      span
        color: white

</style>
