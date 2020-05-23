<template>
  <div class="chat-login">
    <img alt="Vue Chat" src="../assets/logo.png" class="chat-logo" />
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
        @keyup.enter="submit"
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

export default {
  name: 'Login',
  data: () => ({
    user: null,
    form: {
      email: null,
      password: null
    }
  }),
  mounted () {
    this.user = new User()
    this.form.email = storageEmail(this.$route)
  },
  methods: {
    async login () {
      try {
        var response = await this.user.login(this.form)
        console.log(response)
        console.log('Usuário logado com sucesso!')
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
