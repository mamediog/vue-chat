<template>
  <div class="chat-login">
    <img alt="Vue Chat" :src="`${baseURL}img/icons/logo.png`" class="chat-logo" />
    <form @submit.prevent="login" class="chat-form__login">
      <input
        type="email"
        v-model="form.email"
        placeholder="Digite seu email @"
        class="chat-login__input email"
        readonly
      />
      <input
        type="password"
        v-model="form.password"
        placeholder="Sua senha"
        class="chat-initial__input"
      />
      <button class="chat-btn chat-login__btn">Continuar</button>
    </form>
  </div>
</template>

<script>
import User from '@/api/user'
import storageEmail from '@/utils/auth/storageEmail'
import basePath from '@/utils/baseURL'

export default {
  name: 'Login',
  data: () => ({
    user: null,
    form: {
      email: null,
      password: null
    }
  }),
  computed: {
    baseURL () {
      return basePath.path
    }
  },
  mounted () {
    this.user = new User()
    this.form.email = storageEmail(this.$route)
  },
  methods: {
    async login () {
      try {
        var response = await this.user.login(this.form)
        localStorage.setItem('user_token', response.token)
        this.user.tryAuthJWT()

        this.$router.push('/home')
      } catch (error) {
        console.log(error.response)
      }
    }
  }
}
</script>

<style lang="sass" scoped>
@import '../sass/colors'

.chat-login
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

  .chat-form__login
    width: 100%
    display: flex
    flex-flow: column
    align-items: center
    justify-content: center

  .chat-login__input.email
    height: 20px
    padding: 10px
    margin-bottom: 10px
    width: 300px
    align-self: flex-start
    font-size: 15px
    border: none
    border-radius: $radius
    background: $inputBorder
    text-align: center
    color: $inputTextColor

  .chat-login__btn
    margin-top: 20px
    width: 200px
    border-radius: $radius
    padding: 20px 0
    font-size: 20px
</style>
