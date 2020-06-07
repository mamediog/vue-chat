<template>
  <div class="chat-register">
    <img alt="Vue Chat" :src="form.image !== null ? form.image : `${baseURL}img/icons/logo.png`" class="chat-logo" id="chat-logo"/>

    <input type="file" class="chat-input__file" @change="onInputChange()" id="chat-input__file" accept=".jpg, .jpeg, .png"/>

    <form @submit.prevent="register" class="chat-register__form">
      <input
        type="text"
        readonly
        v-model="form.email"
        class="chat-input chat-register__input-email"
      />

      <div class="chat-register__form-box chat-col--2">
        <input type="text" v-model="form.name" class="chat-input" placeholder="Nome e Sobrenome" />
        <input type="text" v-model="form.phone" class="chat-input" placeholder="Seu Celular" />
      </div>

      <div class="chat-col--2">
        <div class="chat-input__password">
          <input
            :type="seePass"
            v-model="form.password"
            class="chat-input"
            placeholder="Nova Senha"
          />
          <span class="chat-seepass" v-if="seePass === 'password'" @click="hideAndShowPass">Espiar</span>
          <span class="chat-seepass" v-else @click="hideAndShowPass">Esconder</span>
        </div>

        <input
          :type="seePass"
          v-model="form.confirm"
          class="chat-input"
          placeholder="Confirmar Senha"
        />
      </div>

      <button type="submit" class="chat-btn chat-register__btn">Confirmar e Cadastrar</button>
    </form>
  </div>
</template>

<script>
import User from '@/api/user'
import basePath from '@/utils/baseURL'
import storageEmail from '@/utils/auth/storageEmail'

export default {
  name: 'Home',
  mounted () {
    this.user = new User()
    this.form.email = storageEmail(this.$route)
  },
  computed: {
    baseURL () {
      return basePath.path
    }
  },
  data: () => ({
    seePass: 'password',
    isTokenized: false,
    user: null,
    form: {
      email: null,
      name: null,
      phone: null,
      password: null,
      confirm: null,
      image: null
    }
  }),
  methods: {
    hideAndShowPass () {
      this.seePass = this.seePass === 'password' ? 'text' : 'password'
    },
    async register () {
      try {
        var response = await this.user.register(this.form)
        console.log(response)
        console.log('Usuário cadastrado com sucesso!')
      } catch (error) {
        console.log(error.response)
      }
    },
    onInputChange (elem) {
      var file = document.getElementById('chat-input__file').files[0]
      var reader = new FileReader()

      reader.addEventListener('load', (event) => {
        this.form.image = event.target.result
      })

      if (file) {
        reader.readAsDataURL(file)
      } else {
        this.form.image = null
      }
    }
  }
}
</script>

<style lang="sass" scoped>
@import '../sass/style.sass'

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

  .chat-register__form
    width: 100%
    display: flex
    flex-flow: column
    justify-content: flex-start
    align-items: center
    input
      margin-bottom: 10px

    .chat-register__input-email
      width: calc(100% - 30px)
      background: #e1e1e1
      font-size: 20px
      height: 55px

  .chat-logo
    position: fixed
    top: 25%
    width: 100px

  .chat-register__btn
    margin-top: 10px
    width: 250px
    border-radius: $radius
    padding: 0 20px
    height: 50px
    font-size: 18px
    align-self: right
</style>
