<template>
  <div class="chat-verify">
    <img alt="Vue Chat" :src="`${baseURL}img/icons/logo.png`" class="chat-logo">
    <input type="email" @keyup.enter="hasUser(email)" v-model="email" placeholder="Digite seu email @" class="chat-initial__input">
    <button @click="hasUser(email)" class="chat-btn chat-verify__btn">Continuar</button>
  </div>
</template>

<script>
import User from '@/api/user'
import basePath from '@/utils/baseURL'

export default {
  name: 'Home',
  data: () => ({
    email: null,
    user: null
  }),
  computed: {
    baseURL () {
      return basePath.path
    }
  },
  methods: {
    async hasUser (email) {
      this.user = new User()
      var response = await this.user.hasUser(email)

      if (response.status === true) {
        this.$router.push({ path: '/login', name: 'Login', params: { email: email } })
      } else {
        if (this.email !== '') {
          this.$router.push({ path: '/register', name: 'Register', params: { email: email } })
        }
      }
    }
  }
}
</script>

<style lang="sass" scoped>
@import '../sass/style.sass'

.chat-verify
  position: fixed
  top: calc((100% - 450px) / 2)
  left: calc((100% - 700px) / 2)
  width: 700px
  height: 450px
  border-radius: 10px
  display: flex
  flex-flow: column
  align-items: center
  justify-content: center

  .chat-logo
    position: fixed
    top: 15%

  .chat-verify__btn
    margin-top: 20px
    width: 200px
    border-radius: $radius
    padding: 20px 0
    font-size: 20px

</style>
