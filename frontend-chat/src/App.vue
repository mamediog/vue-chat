<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>
// MIXINS
import VerifyLogin from '@/utils/auth/setUserLogin'

export default {
  mixins: [VerifyLogin],
  data: () => ({
    user: null
  }),
  async created () {
    this.user = await this.isLogged()
    if (!this.user) {
      localStorage.removeItem('user_token')
      this.$router.push('/verify')
    } else {
      this.$router.push('/')
    }
  }
}
</script>

<style lang="sass">
@import './sass/style'
html
  scroll-behavior: smooth

*
  font-family: Avenir, Helvetica, Arial, sans-serif
  margin: 0
  padding: 0

*
  &:focus
    outline: none

body
  background-color: #ebebeb

#app
  -webkit-font-smoothing: antialiased
  -moz-osx-font-smoothing: grayscale
  text-align: center
  color: #2c3e50
  width: 100%
  height: 100%
  position: fixed
  &:after
    content: ''
    width: 100%
    height: 200px
    background: rgb(2,0,36)
    background: linear-gradient(0deg, rgba(2,0,36,0) 0%, rgba(53,121,9,0) 10%, rgba(0,150,136,1) 85%)
    position: fixed
    top: 0
    left: 0

::-webkit-scrollbar-track
  background-color: transparent

::-webkit-scrollbar
  width: 5px
  background-color: transparent

::-webkit-scrollbar-thumb
  background-color: #ccc

input, textarea, select
  &:focus
    outline: none

.chat-initial__input
  border: 2px solid $inputBorder
  width: calc(80% - 40px)
  height: 50px
  border-radius: $radius
  font-size: 30px
  padding: 20px
  color: #595959
  &::placeholder
    color: #d1d1d1

.chat-col--2
  display: flex
  flex-flow: row
  align-items: center
  justify-content: space-between
  width: 100%
  input
    width: calc(49% - 30px) !important
    &::placeholder
      color: #d1d1d1
  .chat-input__password
    width: 49%
    input
      width: calc(100% - 30px) !important

.chat-input
  border: none
  width: calc(100% - 30px)
  padding: 0 15px
  height: 40px
  box-shadow: 1px 1px 10px 1px rgba(0,0,0,0.07)
  border: 1px solid $inputBorder
  border-radius: $radius
  color: $inputTextColor

.chat-input__password
  position: relative
  .chat-seepass
    position: absolute
    right: 10px
    top: 17px
    font-size: 11px
    cursor: pointer

.chat-btn
  border: none
  background: $primaryColor
  color: white
  padding: 10px 20px
  cursor: pointer
</style>
