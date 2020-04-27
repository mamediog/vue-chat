<template>
  <div class="chat-register">
    <img alt="Vue Chat" src="../assets/logo.png" class="chat-logo">
    <form class="chat-register__form">
      <input type="text" readonly v-model="email" class="chat-form__input">
    </form>
  </div>
</template>

<script>
import User from '@/api/user'
export default {
  name: 'Home',
  mounted () {
    if (this.$route !== undefined && this.$route.params.email !== undefined) {
      this.email = this.$route.params.email
      localStorage.setItem('user_email', this.email)
    }

    if (localStorage.getItem('user_email') !== undefined && localStorage.getItem('user_email') !== '') {
      this.email = localStorage.getItem('user_email')
    }

    setTimeout(() => {
      if (this.email === '' || this.email === null) {
        this.$router.push('/')
      }
    }, 100)
  },
  data: () => ({
    email: null,
    user: null
  }),
  methods: {
    async hasUser (email) {
      this.user = new User()
      var response = await this.user.hasUser(email)

      if (response.status === 'success') {
        console.log('Você existe')
      } else {
        this.$router.push('/register')
      }
    }
  }
}
</script>

<style lang="sass" scoped>
@import '../sass/colors'

.chat-register
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

  .chat-register__input
    border: none
    box-shadow: 1px 1px 10px 1px rgba(0,0,0,0.1)
    width: calc(700px - 40px)
    height: 70px
    border-radius: 10px
    font-size: 30px
    padding: 20px
    color: #595959
    &::placeholder
      color: #d1d1d1

  .chat-register__btn
    margin-top: 20px
    width: 200px
    border-radius: $radius
    padding: 20px 0
    font-size: 20px

</style>
